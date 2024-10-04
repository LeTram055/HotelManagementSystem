<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rooms;
use App\Models\Types;
use App\Models\RoomStatuses;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Rooms::all();
        return view('admin/room/index')
            ->with('rooms', $rooms);
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
        return redirect()->route('admin.room.index');
    }
    
    public function destroy(Request $request)
    {
        $request->validate([
            'room_id' => 'required|integer|exists:rooms,room_id',
        ]);
        
        $room = Rooms::find($request->room_id);
        $room->destroy($request->room_id);
        return redirect()->route('admin.room.index');
    }
}