<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\TypeImages;
use App\Models\Types;
use Illuminate\Support\Facades\Storage;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TypeImagesExport;

class TypeImageController extends Controller
{
    public function index(Request $request) {
        $sortField = $request->input('sort_field', 'image_id'); // Mặc định sắp xếp theo image_id
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định sắp xếp tăng dần

        $query = TypeImages::query();
        // Kiểm tra nếu có input tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            
            $query->where('image_id', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('type', function($q) use ($searchTerm) {
                $q->where('type_name', 'like', '%' . $searchTerm . '%');
                })
                ->orWhere('image_url', 'like', '%' . $searchTerm . '%');
                
        }
        $typeImages = $query->orderBy($sortField, $sortDirection)->get();
        return view('admin.typeimage.index')
        ->with('typeImages', $typeImages)
        ->with('sortField', $sortField)
        ->with('sortDirection', $sortDirection);
    }

    public function create()
    {
        $types = Types::all();
        return view('admin/typeimage/create')
            ->with('types', $types);
    }

    public function save(Request $request)
    {
        //ràng buộc 
            $validation = $request->validate(['image_url' => 'required|file|image|mimes:jpeg,png,gif,webp|max:2048',]);
    
        if ($request->hasFile('image_url')) {
            $typeImage = new TypeImages();
            $type = $request->type_id;
            $image = $request->file('image_url'); //lấy file hình ảnh từ form

            $originName = $image->getClientOriginalName();
            $image->storeAs('uploads', $originName, 'public'); // Lưu file vào /storage/app/public/uploads
            
            $typeImage->type_id = $type;
            $typeImage->image_url = $originName;
            $typeImage->save();

            Session::flash('alert-info', 'Thêm mới thành công ^^~!!!');
            return redirect()->route('admin.typeimage.index');
            
        } else {
            Session::flash('alert-danger', 'Không có file hình ảnh nào được tải lên.');
            return redirect()->route('admin.typeimage.create');
        }
    }

    public function edit(Request $request)
    {
        $typeImage = TypeImages::find($request->image_id);
        $types = Types::all();
        return view('admin/typeimage/edit')
        ->with('typeImage', $typeImage)
        ->with('types', $types);
    }

    public function update(Request $request)
    {
        $typeImage = TypeImages::find($request->image_id);
        $newImage = $request->file('image_url');

        $typeImage->type_id = $request->type_id;

        if($request->hasFile('image_url')) {
            //xóa file cũ
            $oldImage = $typeImage->image_url;
            Storage::disk('public')->delete('uploads/' . $oldImage);

            $originName = $newImage->getClientOriginalName();
            $newImage->storeAs('uploads', $originName, 'public');
            $typeImage->image_url = $originName;
        }
        
        $typeImage->save();
        Session::flash('alert-info', 'Cập nhật thành công ^^~!!!');
        return redirect()->route('admin.typeimage.index');
    }
    
    public function destroy(Request $request)
    {
        $request->validate([
            'image_id' => 'required|integer|exists:type_images,image_id',
        ]);
        
        $typeImage = TypeImages::find($request->image_id);
        if ($typeImage) {
            $oldImage = $typeImage->image_url;
            Storage::disk('public')->delete('uploads/' . $oldImage);
            $typeImage->destroy($request->image_id);
            Session::flash('alert-info', 'Xóa thành công ^^~!!!');
        } else {
            Session::flash('alert-danger', 'Không tìm thấy hình ảnh.');
        }
        return redirect()->route('admin.typeimage.index');
    }

    public function exportExcel() 
    {
        return Excel::download(new TypeImagesExport, 'typeimages.xlsx');
    }
}