<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\RoomStatuses;

class RoomStatusController extends Controller
{
    public function index()
    {
        $roomStatuses = RoomStatuses::all();
        return view('admin/roomstatus/index')
            ->with('roomStatuses', $roomStatuses);
    }

    public function create()
    {
        return view('admin/roomstatus/create');
    }

    public function save(Request $request)
    {
        $roomStatus = new RoomStatuses();
        $roomStatus->status_name = $request->status_name;
        $roomStatus->save();
        Session::flash('alert-info', 'Thêm mới thành công ^^~!!!');
        return redirect()->route('admin.roomstatus.index');
    }

    public function edit(Request $request)
    {
        $roomStatus = RoomStatuses::find($request->status_id);
        return view('admin/roomstatus/edit')
        ->with('roomStatus', $roomStatus);
    }

    public function update(Request $request)
    {
        $roomStatus = RoomStatuses::find($request->status_id);
        $roomStatus->status_name = $request->status_name;
        $roomStatus->save();
        Session::flash('alert-info', 'Cập nhật thành công ^^~!!!');
        return redirect()->route('admin.roomstatus.index');
    }
    
    public function destroy(Request $request)
    {
        $request->validate([
            'status_id' => 'required|integer|exists:room_statuses,status_id',
        ]);
        
        $roomStatus = RoomStatuses::find($request->status_id);
        $roomStatus->destroy($request->status_id);
        Session::flash('alert-info', 'Xóa thành công ^^~!!!');
        return redirect()->route('admin.roomstatus.index');
    }
}