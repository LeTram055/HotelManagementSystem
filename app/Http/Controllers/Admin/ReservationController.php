<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Reservations;
use App\Models\Customers;
use App\Models\Rooms;
use App\Models\RoomReservation;
use App\Models\Types;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservations::with(['customer', 'rooms.type'])->get();
        return view('admin/reservation/index')
            ->with('reservations', $reservations);
    }

    public function create()
    {
        $customers = Customers::all();
        $types = Types::all();
        $rooms = Rooms::where('status_id', 1)->get();
        return view('admin/reservation/create')
        ->with('customers', $customers)
        ->with('types', $types)
        ->with('rooms', $rooms);
        
    }

    public function save(Request $request)
    {

        // Kiểm tra dữ liệu đầu vào từ form
        $validatedData = $request->validate([
        'customer_id' => 'required|exists:customers,customer_id',
        'room_ids' => 'required|array', // Thay đổi để chấp nhận mảng
        'room_ids.*' => 'exists:rooms,room_id',
        'reservation_checkin' => 'required|date|after_or_equal:today',
        'reservation_checkout' => 'required|date|after:reservation_checkin',
        'reservation_status' => 'required|in:Pending,Confirmed,Checked-in,Checked-out,Cancelled',
        ], [
        'customer_id.required' => 'Vui lòng chọn khách hàng.',
        'room_ids.required' => 'Vui lòng chọn loại phòng.',
        'reservation_checkin.required' => 'Vui lòng chọn ngày nhận phòng.',
        'reservation_checkin.after_or_equal' => 'Ngày nhận phòng phải từ ngày hôm nay trở đi.',
        'reservation_checkout.required' => 'Vui lòng chọn ngày trả phòng.',
        'reservation_checkout.after' => 'Ngày trả phòng phải sau ngày nhận phòng.',
        'reservation_status.required' => 'Vui lòng chọn trạng thái đặt phòng.',
        ]);
        
        // Lưu thông tin đặt phòng
        $reservation = new Reservations();
        $reservation->customer_id = $request->customer_id;
        $reservation->reservation_date = now();
        $reservation->reservation_checkin = $request->reservation_checkin;
        $reservation->reservation_checkout = $request->reservation_checkout;
        $statusMapping = [
        'Pending' => 'Chờ xác nhận',
        'Confirmed' => 'Đã xác nhận',
        'Checked-in' => 'Đã nhận phòng',
        'Checked-out' => 'Đã trả phòng',
        'Cancelled' => 'Đã hủy'
        ];

        // Lưu trạng thái bằng tiếng Việt
        $reservation->reservation_status = $statusMapping[$request->reservation_status];
        $reservation->save();
        
        // Lưu thông tin các phòng đã đặt
        foreach ($request->room_ids as $room_id) {
            $roomReservation = new RoomReservation();
            $roomReservation->reservation_id = $reservation->reservation_id;
            $roomReservation->room_id = (int) $room_id;
            $roomReservation->save();

            // Cập nhật trạng thái phòng thành "Đã đặt" (status_id = 2)
            $room = Rooms::find($room_id);
            $room->update(['status_id' => 2]);
    }
        
        Session::flash('alert-info', 'Thêm mới thành công ^^~!!!');
        return redirect()->route('admin.reservation.index');
    }

    public function edit(Request $request)
    {
        $reservation = Reservations::find($request->reservation_id);
        $customers = Customers::all();
        $types = Types::all();

        // Lấy các phòng trống hoặc phòng đã được chọn
       $rooms = Rooms::where('status_id', 1)
        ->orWhereHas('reservations', function ($query) use ($request) {
            $query->where('reservations.reservation_id', $request->reservation_id);
        })->get();
        return view('admin/reservation/edit')
        ->with('reservation', $reservation)
        ->with('customers', $customers)
        ->with('types', $types)
        ->with('rooms', $rooms);
    }

    public function update(Request $request)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'room_ids' => 'required|array', // Thay đổi để chấp nhận mảng
            'room_ids.*' => 'exists:rooms,room_id',
            'reservation_checkin' => 'required|date|after_or_equal:today',
            'reservation_checkout' => 'required|date|after:reservation_checkin',
            'reservation_status' => 'required|in:Pending,Confirmed,Checked-in,Checked-out,Cancelled',
        ], [
            'customer_id.required' => 'Vui lòng chọn khách hàng.',
            'room_ids.required' => 'Vui lòng chọn loại phòng.',
            'reservation_checkin.required' => 'Vui lòng chọn ngày nhận phòng.',
            'reservation_checkin.after_or_equal' => 'Ngày nhận phòng phải từ ngày hôm nay trở đi.',
            'reservation_checkout.required' => 'Vui lòng chọn ngày trả phòng.',
            'reservation_checkout.after' => 'Ngày trả phòng phải sau ngày nhận phòng.',
            'reservation_status.required' => 'Vui lòng chọn trạng thái đặt phòng.',
        ]);

        // Cập nhật thông tin đặt phòng
        $reservation = Reservations::find($request->reservation_id);
        $reservation->customer_id = $request->customer_id;
        $reservation->reservation_checkin = $request->reservation_checkin;
        $reservation->reservation_checkout = $request->reservation_checkout;

        $statusMapping = [
            'Pending' => 'Chờ xác nhận',
            'Confirmed' => 'Đã xác nhận',
            'Checked-in' => 'Đã nhận phòng',
            'Checked-out' => 'Đã trả phòng',
            'Cancelled' => 'Đã hủy'
        ];
        $reservation->reservation_status = $statusMapping[$request->reservation_status];
        $reservation->save();

        // Xóa phòng cũ và thêm phòng mới
        $reservation->rooms()->sync($request->room_ids);

        // Cập nhật trạng thái phòng
        foreach ($request->room_ids as $room_id) {
            $room = Rooms::find($room_id);
            $room->update(['status_id' => 2]);
        }

        Session::flash('alert-info', 'Cập nhật thành công!');
        return redirect()->route('admin.reservation.index');
    }
    
    public function destroy(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|integer|exists:reservations,reservation_id',
        ]);
        
        $reservation = Reservations::find($request->reservation_id);
        // Nếu tìm thấy đặt phòng
    if ($reservation) {
        // Xóa tất cả các bản ghi trong room_reservation liên quan đến đặt phòng này
        RoomReservation::where('reservation_id', $reservation->reservation_id)->delete();

        // Cập nhật trạng thái phòng về "trống" (status_id = 1)
        $rooms = Rooms::whereIn('room_id', $reservation->rooms->pluck('room_id'))->get();
        foreach ($rooms as $room) {
            $room->update(['status_id' => 1]);
        }

        // Xóa đặt phòng
        $reservation->delete();

        Session::flash('alert-info', 'Xóa đặt phòng thành công ^^~!!!');
    } else {
        Session::flash('alert-danger', 'Không tìm thấy đặt phòng!');
    }
        return redirect()->route('admin.reservation.index');
    }
}