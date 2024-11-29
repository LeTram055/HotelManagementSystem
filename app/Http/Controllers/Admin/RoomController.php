<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Rooms;
use App\Models\Types;
use App\Models\RoomStatuses;
use App\Models\Reservations;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RoomsExport;


class RoomController extends Controller
{

    public function index(Request $request)

    {

        $request->validate([
        
        'end_date' => 'after_or_equal:start_date', // Kiểm tra end_date >= start_date
    ], [
        
        'end_date' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',
    ]);

        // Nhận dữ liệu từ form
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $roomType = $request->input('room_type', ''); // Lọc theo loại phòng
        $roomStatus = $request->input('room_status', ''); // Lọc theo trạng thái phòng
        $searchTerm = $request->input('search', ''); // Tìm kiếm theo tên phòng
        
        // Query tất cả các phòng
        $rooms = Rooms::with('type', 'status');

        // Lọc theo loại phòng
        if ($roomType) {
            $rooms->where('type_id', $roomType);
        }

        
        // Tìm kiếm theo tên phòng
        if ($searchTerm) {
            $rooms->where('room_name', 'like', '%' . $searchTerm . '%');
        }

        $rooms = $rooms->get();

    
        // Query các đơn đặt phòng trong khoảng thời gian
        $reservations = Reservations::where(function ($query) use ($startDate, $endDate) {
            if ($startDate === $endDate) {
        // Kiểm tra xem start_date có nằm trong khoảng checkin và checkout của đơn đặt phòng không
        $query->where(function($q) use ($startDate) {
            $q->where('reservation_checkin', '<=', $startDate)
              ->where('reservation_checkout', '>=', $startDate);
        });
    } else {
        // Nếu ngày bắt đầu và ngày kết thúc khác nhau, sử dụng whereBetween như bình thường
        $query->whereBetween('reservation_checkin', [$startDate, $endDate])
              ->orWhereBetween('reservation_checkout', [$startDate, $endDate]);
    }
        })->get();
        
        // Duyệt qua từng phòng và xác định trạng thái
        $roomStatuses = [];
        foreach ($rooms as $room) {
            $roomStatusF = 'Trống'; // Mặc định

            $foundInReservation = false;
            foreach ($reservations as $reservation) {
                if ($reservation->rooms->contains('room_id', $room->room_id)) {
                    $foundInReservation = true;
                    
                    if ($reservation->reservation_status === 'Đã nhận phòng') {
                        $roomStatusF = 'Đang sử dụng';
                        break;
                    } elseif ($reservation->reservation_status === 'Đã xác nhận') {
                        $roomStatusF = 'Đã đặt';
                    }
                    
                }
            }

            if (!$foundInReservation) {
                $roomStatusF = $room->status->status_name === 'Đang sửa' ? 'Đang sửa' : 'Trống';
            }

            $roomStatuses[$room->room_id] = $roomStatusF;
        }

        // Lọc theo trạng thái phòng
        if ($roomStatus) {
            $rooms = $rooms->filter(function ($room) use ($roomStatus, $roomStatuses) {
                return $roomStatuses[$room->room_id] === $roomStatus;
            });
        }

        $roomTypes = Types::all();
        // Trả về view với dữ liệu
        return view('admin.room.index', compact('rooms', 'roomStatuses', 'startDate', 'endDate', 'roomType', 'roomStatus', 'roomTypes'));
    }




    // public function index(Request $request) {
    //     $sortField = $request->input('sort_field', 'room_id'); // Mặc định sắp xếp theo room_id
    //     $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định sắp xếp tăng dần
        
    //     //$query = Rooms::query();

    //     $query = Rooms::join('types', 'rooms.type_id', '=', 'types.type_id')
    //         ->join('room_statuses', 'rooms.status_id', '=', 'room_statuses.status_id')
    //         ->select('rooms.*', 'types.type_name', 'room_statuses.status_name');

    //     if ($request->filled('search')) {
    //         $searchTerm = $request->input('search');
            
    //         $query->where('room_id', 'like', '%' . $searchTerm . '%')
    //             ->orWhere(function ($q) use ($searchTerm) {
    //             $q->where('room_name', 'like', '%' . $searchTerm . '%')
    //                 ->orWhere('type_name', 'like', '%' . $searchTerm . '%')
    //                 ->orWhere('status_name', 'like', '%' . $searchTerm . '%')
    //                 ->orWhere('room_note', 'like', '%' . $searchTerm . '%');
    //             });
    //     }

    //     if (in_array($sortField, ['type_name', 'room_name', 'status_name'])) {
    //         $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
    //     } elseif ($sortField == 'room_id') {
    //         // Sắp xếp giá theo kiểu số
    //         $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
    //     } 
    //     else {
    //         $query->orderByRaw("CONVERT(room_id USING utf8) COLLATE utf8_unicode_ci asc");
    //     }

    //     $rooms = $query->get();
    //     return view('admin.room.index')
    //     ->with('rooms', $rooms)
    //     ->with('sortField', $sortField)
    //     ->with('sortDirection', $sortDirection);
    // }

    public function create()
    {
        $types = Types::all();
        $roomStatuses = RoomStatuses::all();
        return view('admin/room/create')
        ->with('types', $types)
        ->with('roomStatuses', $roomStatuses);
    }

    public function save(Request $request)
    {
        $request->validate([
            'room_name' => 'required|unique:rooms',
            'type_id' => 'required|integer|exists:types,type_id',
            'status_id' => 'required|integer|exists:room_statuses,status_id',
            
        ], [
            'room_name.required' => 'Tên phòng không được để trống.',
            'room_name.unique' => 'Tên phòng đã tồn tại. Vui lòng chọn tên khác.',
            'type_id.required' => 'Loại phòng không được để trống.',
            'status_id.required' => 'Trạng thái phòng không được để trống.',
            
        ]);

        $room = new Rooms();
        $room->room_name = $request->room_name;
        $room->type_id = $request->type_id;
        $room->status_id = $request->status_id;
        $room->room_note = $request->room_note;
        $room->save();
        Session::flash('alert-info', 'Thêm mới thành công ^^~!!!');
        return redirect()->route('admin.room.index');
    }

    public function edit(Request $request)
    {
        $room = Rooms::find($request->room_id);
        $types = Types::all();
        $roomStatuses = RoomStatuses::all();
        return view('admin/room/edit')
        ->with('room', $room)
        ->with('types', $types)
        ->with('roomStatuses', $roomStatuses);
    }

    public function update(Request $request)
    {   
        $request->validate([
            'room_id' => 'required|exists:rooms,room_id',
            'room_name' => 'required|unique:rooms',
            'type_id' => 'required|integer|exists:types,type_id',
            'status_id' => 'required|integer|exists:room_statuses,status_id',
            
        ], [
            'room_name.required' => 'Tên phòng không được để trống.',
            'room_name.unique' => 'Tên phòng đã tồn tại. Vui lòng chọn tên khác.',
            'type_id.required' => 'Loại phòng không được để trống.',
            'status_id.required' => 'Trạng thái phòng không được để trống.',
            
        ]);

        $room = Rooms::find($request->room_id);
        $room->room_name = $request->room_name;
        $room->type_id = $request->type_id;
        $room->status_id = $request->status_id;
        $room->room_note = $request->room_note;
        $room->save();
        Session::flash('alert-info', 'Cập nhật thành công ^^~!!!');
        return redirect()->route('admin.room.index');
    }
    
    public function destroy(Request $request)
    {
        $request->validate([
            'room_id' => 'required|integer|exists:rooms,room_id',
        ]);
        
        $room = Rooms::find($request->room_id);

        // Kiểm tra xem phòng đã được đặt chưa
        if($room->reservations()->exists()) {
            Session::flash('alert-danger', 'Không thể xóa phòng đã được đặt.');
            return redirect()->route('admin.room.index');
        }

        $room->delete();
        Session::flash('alert-info', 'Xóa thành công ^^~!!!');
        return redirect()->route('admin.room.index');
    }

    public function exportExcel() 
    {
        return Excel::download(new RoomsExport, 'rooms.xlsx');
    }
}