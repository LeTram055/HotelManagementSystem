<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Facilities;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FacilitiesExport;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'facility_id'); // Mặc định sắp xếp theo facility_id
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định sắp xếp tăng dần

        $query = Facilities::query();
        // Kiểm tra nếu có input tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            // Tìm kiếm 
            $query->where('facility_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('facility_id', 'like', '%' . $searchTerm . '%')
                ->orWhere('facility_description', 'like', '%' . $searchTerm . '%');
        }

        if (in_array($sortField, ['facility_name', 'facility_description'])) {
            $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
        } elseif ($sortField == 'facility_id') {
            // Sắp xếp giá theo kiểu số
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        } 
        else {
            $query->orderByRaw("CONVERT(facility_id USING utf8) COLLATE utf8_unicode_ci asc");
        }

        $facilities = $query->get();
        return view('admin.facility.index')
        ->with('facilities', $facilities)
        ->with('sortField', $sortField)
        ->with('sortDirection', $sortDirection);
    }

    public function create()
    {
        return view('admin/facility/create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'facility_name' => 'required|string|unique:facilities,facility_name',
            'facility_description' => 'required|string',
        ], [
            'facility_name.required' => 'Vui lòng nhập tên thiết bị',
            'facility_name.unique' => 'Tên thiết bị đã tồn tại',
            'facility_description.required' => 'Vui lòng nhập mô tả thiết bị',
        ]);

        $facility = new Facilities();
        $facility->facility_name = $request->facility_name;
        $facility->facility_description = $request->facility_description;
        $facility->save();
        Session::flash('alert-info', 'Thêm mới thành công ^^~!!!');
        return redirect()->route('admin.facility.index');
    }

    public function edit(Request $request)
    {
        $facility = Facilities::find($request->facility_id);
        return view('admin/facility/edit')
        ->with('facility', $facility);
    }

    public function update(Request $request)
    {
        $request->validate([
            'facility_name' => 'required|string|unique:facilities,facility_name',
            'facility_description' => 'required|string',
        ], [
            'facility_name.required' => 'Vui lòng nhập tên thiết bị',
            'facility_name.unique' => 'Tên thiết bị đã tồn tại',
            'facility_description.required' => 'Vui lòng nhập mô tả thiết bị',
        ]);

        $facility = Facilities::find($request->facility_id);
        $facility->facility_name = $request->facility_name;
        $facility->facility_description = $request->facility_description;
        $facility->save();
        Session::flash('alert-info', 'Cập nhật thành công ^^~!!!');
        return redirect()->route('admin.facility.index');
    }
    
    public function destroy(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|integer|exists:facilities,facility_id',
        ]);
        
        $facility = Facilities::find($request->facility_id);

        if ($facility->types()->exists()) { // Giả sử bạn đã định nghĩa quan hệ trong model RoomStatuses
        Session::flash('alert-danger', 'Không thể xóa thiết bị này vì nó đang được sử dụng trong loại phòng.');
        return redirect()->route('admin.facility.index');
        }

        $facility->destroy($request->facility_id);
        Session::flash('alert-info', 'Xóa thành công ^^~!!!');
        return redirect()->route('admin.facility.index');
    }

    public function exportExcel() 
    {
        return Excel::download(new FacilitiesExport, 'facilities.xlsx');
    }
}