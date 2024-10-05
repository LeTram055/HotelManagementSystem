<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facilities;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Types;

class TypeController extends Controller
{
    public function index()
    {
        $types = Types::all();
        return view('admin/type/index')
            ->with('types', $types);
    }

    public function create()
    {
        $facilities = Facilities::all();
        return view('admin/type/create')
            ->with('facilities', $facilities);
    }

    public function save(Request $request)
    {
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
}