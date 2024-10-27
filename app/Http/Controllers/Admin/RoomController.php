<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Rooms;
use App\Models\Types;
use App\Models\RoomStatuses;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RoomsExport;


class RoomController extends Controller
{
    public function index(Request $request) {
        $sortField = $request->input('sort_field', 'room_id'); // Mặc định sắp xếp theo room_id
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định sắp xếp tăng dần

        $query = Rooms::query();
        // Kiểm tra nếu có input tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            
            $query->where('room_id', 'like', '%' . $searchTerm . '%')
                ->orwhere('room_name', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('type', function($q) use ($searchTerm) {
                $q->where('type_name', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('status', function($q) use ($searchTerm) {
                    $q->where('status_name', 'like', '%' . $searchTerm . '%');
                })
                ->orWhere('room_note', 'like', '%' . $searchTerm . '%');
        }
        $rooms = $query->orderBy($sortField, $sortDirection)->get();
        return view('admin.room.index')
        ->with('rooms', $rooms)
        ->with('sortField', $sortField)
        ->with('sortDirection', $sortDirection);
    }

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
        $room->destroy($request->room_id);
        Session::flash('alert-info', 'Xóa thành công ^^~!!!');
        return redirect()->route('admin.room.index');
    }

    public function exportExcel() 
    {
        return Excel::download(new RoomsExport, 'rooms.xlsx');
    }
}