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

        $query = TypeImages::join('types', 'type_images.type_id', '=', 'types.type_id')
            ->select('type_images.*', 'types.type_name');

        //Kiểm tra nếu có input tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            
            $query->where(function ($q) use ($searchTerm) {
                $q->where('image_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('image_url', 'like', '%' . $searchTerm . '%')
                    ->orWhere('type_name', 'like', '%' . $searchTerm . '%');
            });
                
        }

        
        if ($sortField == 'type_name') {
            $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
        } elseif ($sortField == 'image_id') {
            // Sắp xếp giá theo kiểu số
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        }  
        else {
            $query->orderByRaw("CONVERT(image_id USING utf8) COLLATE utf8_unicode_ci asc");
        }
        $typeImages = $query->get();
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
            $request->validate(['image_url' => 'required|file|image|mimes:jpeg,png,gif,webp|max:2048',
            'type_id' => 'required|integer|exists:types,type_id',
        ], [
            'image_url.required' => 'Vui lòng chọn file hình ảnh',
            'image_url.file' => 'File tải lên không hợp lệ',
            'image_url.image' => 'File tải lên không phải là hình ảnh',
            'image_url.mimes' => 'File tải lên phải có định dạng jpeg, png, gif, webp',
            'image_url.max' => 'Dung lượng file tải lên không được vượt quá 2MB',
            'type_id.required' => 'Vui lòng chọn loại phòng',
            'type_id.integer' => 'Loại phòng không hợp lệ',
            'type_id.exists' => 'Loại phòng không tồn tại',
        ]);
    
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
        $request->validate([
            'image_id' => 'required|integer|exists:type_images,image_id',
            'type_id' => 'required|integer|exists:types,type_id',
            'image_url' => 'file|image|mimes:jpeg,png,gif,webp|max:2048',
        ], [
            'image_id.required' => 'Không tìm thấy hình ảnh',
            'image_id.integer' => 'ID hình ảnh không hợp lệ',
            'image_id.exists' => 'Hình ảnh không tồn tại',
            'type_id.required' => 'Vui lòng chọn loại phòng',
            'type_id.integer' => 'Loại phòng không hợp lệ',
            'type_id.exists' => 'Loại phòng không tồn tại',
            'image_url.file' => 'File tải lên không hợp lệ',
            'image_url.image' => 'File tải lên không phải là hình ảnh',
            'image_url.mimes' => 'File tải lên phải có định dạng jpeg, png, gif, webp',
            'image_url.max' => 'Dung lượng file tải lên không được vượt quá 2MB',
        ]);
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