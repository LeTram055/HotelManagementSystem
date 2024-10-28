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

        //$query = Rooms::query();

        $query = Rooms::join('types', 'rooms.type_id', '=', 'types.type_id')
            ->join('room_statuses', 'rooms.status_id', '=', 'room_statuses.status_id')
            ->select('rooms.*', 'types.type_name', 'room_statuses.status_name');

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            
            $query->where('room_id', 'like', '%' . $searchTerm . '%')
                ->orWhere(function ($q) use ($searchTerm) {
                $q->where('room_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('type_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('status_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('room_note', 'like', '%' . $searchTerm . '%');
                });
        }

        if (in_array($sortField, ['type_name', 'room_name', 'status_name'])) {
            $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
        } elseif ($sortField == 'room_id') {
            // Sắp xếp giá theo kiểu số
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        } 
        else {
            $query->orderByRaw("CONVERT(room_id USING utf8) COLLATE utf8_unicode_ci asc");
        }

        $rooms = $query->get();
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