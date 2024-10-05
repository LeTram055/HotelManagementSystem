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
        <h3 class="text-center title2">Cập nhật hình ảnh loại phòng</h3>
        <form name="frmEdit" id="frmCreate" method="post" enctype="multipart/form-data"
            action="{{ route('admin.typeimage.update') }}">
            @csrf
            <input type="hidden" name="image_id" value="{{ $typeImage->image_id }}">

            <select class="form-control" id="type_id" name="type_id">
                @foreach ($types as $type)
                @if($type->type_id == $typeImage->type_id)
                <option value="{{ $type->type_id }}" selected>{{ $type->type_name }}</option>
                @else
                <option value="{{ $type->type_id }}">{{ $type->type_name }}</option>
                @endif
                @endforeach
            </select>
            <div class="form-group">
                <label for="image_url">Hình sản phẩm:</label>
                <div class="">
                    <img class="img-type" src="{{asset('storage/uploads/'. $typeImage->image_url) }}">
                    <br />
                    <a href="{{asset('storage/uploads/'. $typeImage->image_url) }}">{{ $typeImage->image_url }}</a>
                </div>
                <input type="file" class="form-control" id="image_url" name="image_url" value="">
            </div>
            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection