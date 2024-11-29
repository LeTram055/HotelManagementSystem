<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\RoomStatuses;

use App\Exports\RoomStatusesExport;
use Maatwebsite\Excel\Facades\Excel;

class RoomStatusController extends Controller
{

    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'status_id'); // Mặc định sắp xếp theo status_id
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định sắp xếp tăng dần

        $query = RoomStatuses::query();
        // Kiểm tra nếu có input tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            // Tìm kiếm theo tên, CCCD, email, địa chỉ
            $query->where('status_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('status_id', 'like', '%' . $searchTerm . '%');
        }

        if ($sortField == 'status_name') {
            $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
        } elseif ($sortField == 'status_id') {
            // Sắp xếp giá theo kiểu số
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        }
        else {
            $query->orderByRaw("CONVERT(type_id USING utf8) COLLATE utf8_unicode_ci asc");
        }

        $roomStatuses = $query->get();
        return view('admin.roomstatus.index')
        ->with('roomStatuses', $roomStatuses)
        ->with('sortField', $sortField)
        ->with('sortDirection', $sortDirection);
    }

    public function create()
    {
        return view('admin/roomstatus/create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'status_name' => 'required|string|unique:room_statuses,status_name',

        ], [
            'status_name.required' => 'Vui lòng nhập tên trạng thái phòng',
            'status_name.unique' => 'Tên trạng thái phòng đã tồn tại',
        ]);

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
        $request->validate([
            'status_id' => 'required|integer|exists:room_statuses,status_id',
            'status_name' => 'required|string|unique:room_statuses,status_name',
        ], [
            'status_name.required' => 'Vui lòng nhập tên trạng thái phòng',
            'status_name.unique' => 'Tên trạng thái phòng đã tồn tại',
        ]);
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

        if ($roomStatus->rooms()->exists()) { // Giả sử bạn đã định nghĩa quan hệ trong model RoomStatuses
        Session::flash('alert-danger', 'Không thể xóa trạng thái này vì nó đang được sử dụng trong phòng.');
        return redirect()->route('admin.roomstatus.index');
    }
    
        $roomStatus->delete();
        Session::flash('alert-info', 'Xóa thành công ^^~!!!');
        return redirect()->route('admin.roomstatus.index');
    }

    public function exportExcel()
    {
        return Excel::download(new RoomStatusesExport, 'roomStatuses.xlsx');
    }
}