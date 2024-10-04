@extends('admin/layouts/master')

@section('title')
Quản lý loại phòng
@endsection

@section('feature-title')
Quản lý loại phòng
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Cập nhật loại phòng</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.type.update') }}">
            @csrf
            <input type="hidden" name="type_id" value="{{ $type->type_id }}">

            <div class="form-group">
                <label for="type_name">Tên loại phòng:</label>
                <input type="text" class="form-control" id="type_name" name="type_name" value="{{ $type->type_name }}">
                @error('type_name')
                <small id="type_name" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="type_price">Giá:</label>
                <input type="text" class="form-control" id="type_price" name="type_price"
                    value="{{ $type->type_price }}">
                @error('type_price')
                <small id="type_price" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="type_capacity">Sức chứa</label>
                <input type="text" class="form-control" id="type_capacity" name="type_capacity"
                    value="{{ $type->type_capacity }}">
                @error('type_capacity')
                <small id="type_capacity" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="type_area">Diện tích</label>
                <input type="text" class="form-control" id="type_area" name="type_area" value="{{ $type->type_area }}">
                @error('type_area')
                <small id="type_area" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection