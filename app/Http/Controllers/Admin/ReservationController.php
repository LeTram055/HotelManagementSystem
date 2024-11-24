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

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReservationsExport;

class ReservationController extends Controller
{

    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'reservation_id'); // Mặc định sắp xếp theo reservation_id
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định sắp xếp tăng dần

        $query = Reservations::join('customers', 'reservations.customer_id', '=', 'customers.customer_id')
            ->join('room_reservation', 'reservations.reservation_id', '=', 'room_reservation.reservation_id')
            ->join('rooms', 'room_reservation.room_id', '=', 'rooms.room_id')
            ->join('types', 'rooms.type_id', '=', 'types.type_id')
            ->select('reservations.*', 'customers.customer_name', 'rooms.room_name', 'types.type_name');

        // Kiểm tra nếu có input tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reservations.reservation_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('customers.customer_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('rooms.room_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('types.type_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('reservations.reservation_status', 'like', '%' . $searchTerm . '%')
                    ->orWhere('reservations.reservation_date', 'like', '%' . $searchTerm . '%')
                    ->orWhere('reservations.reservation_checkin', 'like', '%' . $searchTerm . '%')
                    ->orWhere('reservations.reservation_checkout', 'like', '%' . $searchTerm . '%');
            });
        }

        // Kiểm tra nếu cột sắp xếp hợp lệ
        if (in_array($sortField, ['customer_name', 'type_name', 'reservation_status', 'reservation_date', 'reservation_checkin', 'reservation_checkout'])) {
            $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
        } elseif ($sortField == 'reservation_id') {
            // Sắp xếp giá theo kiểu số
            $query->orderByRaw("CAST(reservations.$sortField AS DECIMAL) $sortDirection");
        } else {
            $query->orderByRaw("CONVERT(reservations.reservation_id USING utf8) COLLATE utf8_unicode_ci asc");
        }

        $reservations = $query->get();
        return view('admin.reservation.index')
            ->with('reservations', $reservations)
            ->with('sortField', $sortField)
            ->with('sortDirection', $sortDirection);
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

    public function getAvailableRooms(Request $request)
    {
        $checkin = $request->input('checkin');
        $checkout = $request->input('checkout');

        $availableRooms = Rooms::with('type') // Eager load loại phòng
            ->where(function ($query) use ($checkin, $checkout) {
                // Tìm tất cả các phòng có trạng thái trống hoặc đã đặt mà không bị chồng chéo thời gian
                $query->where('status_id', 1) // Phòng trống
                    ->orWhere(function ($subQuery) use ($checkin, $checkout) {
                        $subQuery->whereDoesntHave('reservations', function ($query) use ($checkin, $checkout) {
                            $query->where(function ($query) use ($checkin, $checkout) {
                                // Loại trừ các phòng có thời gian đặt chồng lên thời gian mới
                                $query->where(function ($query) use ($checkin, $checkout) {
                                    $query->where('reservation_checkin', '<=', $checkout)
                                        ->where('reservation_checkout', '>=', $checkin);
                                });
                            });
                        });
                    });
            })
            ->where('status_id', '!=', 4)
            ->get();

        return response()->json($availableRooms);
    }

    public function save(Request $request)
    {

        // Kiểm tra dữ liệu đầu vào từ form
        $request->validate([
        'customer_id' => 'required|exists:customers,customer_id',
        'room_ids' => 'required|array', 
        'room_ids.*' => 'exists:rooms,room_id',
        'reservation_checkin' => 'required|date|after_or_equal:today',
        'reservation_checkout' => 'required|date|after:reservation_checkin',
        ],[
        'customer_id.required' => 'Vui lòng chọn khách hàng.',
        'room_ids.required' => 'Vui lòng chọn loại phòng.',
        'reservation_checkin.required' => 'Vui lòng chọn ngày nhận phòng.',
        'reservation_checkin.after_or_equal' => 'Ngày nhận phòng phải từ ngày hôm nay trở đi.',
        'reservation_checkout.required' => 'Vui lòng chọn ngày trả phòng.',
        'reservation_checkout.after' => 'Ngày trả phòng phải sau ngày nhận phòng.', 
        
        ]);
        
        // Lưu thông tin đặt phòng
        $reservation = new Reservations();
        $reservation->customer_id = $request->customer_id;
        $reservation->reservation_date = now();
        $reservation->reservation_checkin = $request->reservation_checkin;
        $reservation->reservation_checkout = $request->reservation_checkout;
        $reservation->reservation_status = 'Chờ xác nhận';
        $reservation->save();
        
        // Lưu thông tin các phòng đã đặt
        foreach ($request->room_ids as $room_id) {
            $roomReservation = new RoomReservation();
            $roomReservation->reservation_id = $reservation->reservation_id;
            $roomReservation->room_id = (int) $room_id;
            $roomReservation->save();

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
        $request->validate([
        'room_ids' => 'required|array', 
        'room_ids.*' => 'exists:rooms,room_id',
        ],[
        'room_ids.required' => 'Vui lòng chọn loại phòng.',
        ]);

        // Cập nhật thông tin đặt phòng
        $reservation = Reservations::find($request->reservation_id);

        // Kiểm tra trạng thái cập nhật có phải là 'Đã nhận phòng' hay không
        if ($request->reservation_status == 'Checked-in') {
            // Kiểm tra ngày hiện tại có trùng hoặc sau ngày nhận phòng không
            if (now()->lt($reservation->reservation_checkin)) {
                // Nếu chưa đến ngày nhận phòng, trả về thông báo lỗi
                Session::flash('alert-danger', 'Không thể cập nhật trạng thái thành "Đã nhận phòng" trước ngày nhận phòng.');
                return redirect()->route('admin.reservation.index');
            }
        }
    
        $statusMapping = [
            'Pending' => 'Chờ xác nhận',
            'Confirmed' => 'Đã xác nhận',
            'Checked-in' => 'Đã nhận phòng',
            'Checked-out' => 'Đã trả phòng',
            'Cancelled' => 'Đã hủy'
        ];
        $reservation->reservation_status = $statusMapping[$request->reservation_status];
        $reservation->save();

        // Cập nhật trạng thái phòng
        foreach ($request->room_ids as $room_id) {
            $room = Rooms::find($room_id);
            if ($request->reservation_status == 'Confirmed') {
                $otherReservations = RoomReservation::where('room_id', $room_id)
                ->whereHas('reservation', function($query) {
                    $query->where('reservation_status', 'Đã nhận phòng');
                })
                ->exists();

                if ($otherReservations) {
                    continue; // Bỏ qua cập nhật cho phòng này
                } else {
                    // Nếu không có phòng nào đã nhận, cập nhật thành "Phòng đã đặt"
                    $room->update(['status_id' => 3]); // Phòng đã đặt
                }
            }
            if ($request->reservation_status == 'Checked-in') {
                $room->update(['status_id' => 2]); // Phòng đang sử dụng
            }
            if ($request->reservation_status == 'Checked-out') {
                $room->update(['status_id' => 1]); // Phòng trống
            }

            if ($request->reservation_status == 'Cancelled') {
                // Kiểm tra các đơn đặt phòng khác liên quan đến phòng này
                $otherReservations = RoomReservation::where('room_id', $room_id)
                    ->whereHas('reservation', function($query) {
                        $query->whereIn('reservation_status', ['Đã nhận phòng', 'Đã xác nhận']);
                    })
                    ->exists();

                if (!$otherReservations) {
                    // Nếu không còn đơn nào liên quan, cập nhật phòng về trạng thái trống
                    $room->update(['status_id' => 1]); // Phòng trống
                }
            }
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
        if ($reservation->reservation_status == 'Chờ xác nhận' || $reservation->reservation_status == 'Đã xác nhận') {
            // Lấy danh sách room_id từ reservation
            $roomIds = $reservation->rooms->pluck('room_id');
            // Xóa tất cả các bản ghi trong room_reservation liên quan đến đặt phòng này
            RoomReservation::where('reservation_id', $reservation->reservation_id)->delete();

            // Cập nhật trạng thái phòng
            foreach ($roomIds as $roomId) {
                // Kiểm tra nếu phòng này còn trong danh sách đặt phòng khác
                $roomReservations = RoomReservation::where('room_id', $roomId)->get();

                // Nếu không còn đặt phòng nào khác
                if ($roomReservations->isEmpty()) {
                    // Cập nhật trạng thái về "trống"
                    $room = Rooms::find($roomId);
                    $room->update(['status_id' => 1]); // Phòng trống
                } else {
                    // Nếu còn trong đặt phòng khác
                    // Kiểm tra trạng thái của các đặt phòng này
                    $hasCheckedIn = $roomReservations->contains(function($roomReservation) {
                        return $roomReservation->reservation->reservation_status == 'Đã nhận phòng';
                    });

                    // Nếu có phòng đã nhận thì không cập nhật trạng thái
                    if (!$hasCheckedIn) {
                        $hasConfirmed = $roomReservations->contains(function($roomReservation) {
                            return $roomReservation->reservation->reservation_status == 'Đã xác nhận';
                        });

                        if ($hasConfirmed) {
                            // Nếu còn đơn đặt đã xác nhận, cập nhật trạng thái "Phòng đã đặt"
                            $room = Rooms::find($roomId);
                            $room->update(['status_id' => 3]); // Phòng đã đặt
                        } else {
                            // Nếu không có đơn đặt nào khác đã xác nhận, cập nhật trạng thái về "trống"
                            $room = Rooms::find($roomId);
                            $room->update(['status_id' => 1]); // Phòng trống
                        }
                    }
                }
            }

            // Xóa đặt phòng
            $reservation->delete();

            // Hiển thị thông báo xóa thành công
            Session::flash('alert-info', 'Xóa đặt phòng thành công ^^~!!!');
            } else {
                // Nếu trạng thái đặt phòng không phải "Chờ xác nhận" hoặc "Đã xác nhận"
                Session::flash('alert-danger', 'Chỉ có thể xóa các đặt phòng ở trạng thái "Chờ xác nhận" hoặc "Đã xác nhận".');
            }
        } else {
            // Nếu không tìm thấy đặt phòng
            Session::flash('alert-danger', 'Không tìm thấy đặt phòng!');
        }

        return redirect()->route('admin.reservation.index');
    }

    public function exportExcel() 
    {
        return Excel::download(new ReservationsExport, 'reservations.xlsx');
    }
}