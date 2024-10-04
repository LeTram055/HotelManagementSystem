<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        return view('admin/type/create');
    }

    public function save(Request $request)
    {
        $type = new Types();
        $type->type_name = $request->type_name;
        $type->type_price = $request->type_price;
        $type->type_capacity = $request->type_capacity;
        $type->type_area = $request->type_area;
        $type->save();
        return redirect()->route('admin.type.index');
    }

    public function edit(Request $request)
    {
        $type = Types::find($request->type_id);
        return view('admin/type/edit')
        ->with('type', $type);
    }

    public function update(Request $request)
    {
        $type = Types::find($request->type_id);
        $type->type_name = $request->type_name;
        $type->type_price = $request->type_price;
        $type->type_capacity = $request->type_capacity;
        $type->type_area = $request->type_area;
        $type->save();
        return redirect()->route('admin.type.index');
    }
    
    public function destroy(Request $request)
    {
        $request->validate([
            'type_id' => 'required|integer|exists:types,type_id',
        ]);
        
        $type = Types::find($request->type_id);
        $type->destroy($request->type_id);
        return redirect()->route('admin.type.index');
    }
}