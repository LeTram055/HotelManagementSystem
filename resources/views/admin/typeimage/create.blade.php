@extends('admin/layouts/master')

@section('title')
Quản lý hình ảnh loại phòng
@endsection

@section('feature-title')
Quản lý hình ảnh loại phòng
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Thêm mới hình ảnh loại phòng</h3>
        <form name="frmCreate" id="frmCreate" method="post" enctype="multipart/form-data"
            action="{{ route('admin.typeimage.save') }}">
            @csrf

            <div class="form-group">
                <label for="type_name">Loại phòng:</label>
                <select class="form-control" id="type_id" name="type_id">
                    @foreach ($types as $type)
                    <option value="{{ $type->type_id }}">{{ $type->type_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="image_url">Hình sản phẩm:</label>
                <input type="file" class="form-control" id="image_url" name="image_url" value="">
            </div>

            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection