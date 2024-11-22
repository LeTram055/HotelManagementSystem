<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facilities;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Types;

use App\Exports\TypesExport;
use Maatwebsite\Excel\Facades\Excel;

class TypeController extends Controller
{
    public function index(Request $request) {
        $sortField = $request->input('sort_field', 'type_id'); // Mặc định sắp xếp theo type_id
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định sắp xếp tăng dần

        $query = Types::query();
        // Kiểm tra nếu có input tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            
            $query->where('type_id', 'like', '%' . $searchTerm . '%')
                ->orwhere('type_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('type_price', 'like', '%' . $searchTerm . '%')
                ->orWhere('type_capacity', 'like', '%' . $searchTerm . '%')
                ->orWhere('type_area', 'like', '%' . $searchTerm . '%');
        }

        if ($sortField == 'type_name') {
            $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
        } elseif (in_array($sortField, ['type_id', 'type_price','type_capacity','type_area'])) {
            // Sắp xếp giá theo kiểu số
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        } 
        else {
            $query->orderByRaw("CONVERT(type_id USING utf8) COLLATE utf8_unicode_ci asc");
        }

        $types = $query->get();
        return view('admin.type.index')
        ->with('types', $types)
        ->with('sortField', $sortField)
        ->with('sortDirection', $sortDirection);
    }

    public function create()
    {
        $facilities = Facilities::all();
        return view('admin/type/create')
            ->with('facilities', $facilities);
    }

    public function save(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string|unique:types,type_name',
            'type_price' => 'required|numeric',
            'type_capacity' => 'required|integer',
            'type_area' => 'required|numeric',
        ], [
            'type_name.required' => 'Vui lòng nhập tên loại phòng',
            'type_name.unique' => 'Tên loại phòng đã tồn tại',
            'type_price.required' => 'Vui lòng nhập giá loại phòng',
            'type_price.numeric' => 'Giá loại phòng phải là số',
            'type_capacity.required' => 'Vui lòng nhập số lượng người',
            'type_capacity.integer' => 'Số lượng người phải là số nguyên',
            'type_area.required' => 'Vui lòng nhập diện tích',
            'type_area.numeric' => 'Diện tích phải là số',
        ]);

        $type = new Types();
        $type->type_name = $request->type_name;
        $type->type_price = $request->type_price;
        $type->type_capacity = $request->type_capacity;
        $type->type_area = $request->type_area;
        $type->save();

        // Lưu các tiện nghi của loại phòng
        if ($request->has('facilities')) {
            $type->facilities()->sync($request->facilities);
        }
    
        Session::flash('alert-info', 'Thêm mới thành công ^^~!!!');
        return redirect()->route('admin.type.index');
    }

    public function edit(Request $request)
    {
        $type = Types::find($request->type_id);
        $facilities = Facilities::all();
        return view('admin/type/edit')
        ->with('type', $type)
        ->with('facilities', $facilities);
    }

    public function update(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string|unique:types,type_name',
            'type_price' => 'required|numeric',
            'type_capacity' => 'required|integer',
            'type_area' => 'required|numeric',
        ], [
            'type_name.required' => 'Vui lòng nhập tên loại phòng',
            'type_name.unique' => 'Tên loại phòng đã tồn tại',
            'type_price.required' => 'Vui lòng nhập giá loại phòng',
            'type_price.numeric' => 'Giá loại phòng phải là số',
            'type_capacity.required' => 'Vui lòng nhập số lượng người',
            'type_capacity.integer' => 'Số lượng người phải là số nguyên',
            'type_area.required' => 'Vui lòng nhập diện tích',
            'type_area.numeric' => 'Diện tích phải là số',
        ]);

        $type = Types::find($request->type_id);

        //Cập nhật loai phòng
        $type->type_name = $request->type_name;
        $type->type_price = $request->type_price;
        $type->type_capacity = $request->type_capacity;
        $type->type_area = $request->type_area;
        $type->save();

        //Cập nhật các tiện nghi của loại phòng
        if ($request->has('facilities')) {
            $type->facilities()->sync($request->facilities);
        } else {
            $type->facilities()->detach();
        }
        
        Session::flash('alert-info', 'Cập nhật thành công ^^~!!!');
        return redirect()->route('admin.type.index');
    }
    
    public function destroy(Request $request)
    {
        $request->validate([
            'type_id' => 'required|integer|exists:types,type_id',
        ]);
        
        $type = Types::find($request->type_id);

        // Xóa các tiện nghi của loại phòng
        $type->facilities()->detach();

        // Xóa loại phòng
        $type->destroy($request->type_id);
        Session::flash('alert-info', 'Xóa thành công ^^~!!!');
        return redirect()->route('admin.type.index');
    }

    public function exportExcel() 
    {
        return Excel::download(new TypesExport, 'types.xlsx');
    }
}