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
        <h3 class="text-center title2">Thêm mới loại phòng</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.type.save') }}">
            @csrf

            <div class="form-group">
                <label for="type_name">Tên loại phòng:</label>
                <input type="text" class="form-control" id="type_name" name="type_name" value="">
                @error('type_name')
                <small id="type_name" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="type_price">Giá:</label>
                <input type="text" class="form-control" id="type_price" name="type_price" value="">
                @error('type_price')
                <small id="type_price" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="type_capacity">Sức chứa:</label>
                <input type="text" class="form-control" id="type_capacity" name="type_capacity" value="">
                @error('type_capacity')
                <small id="type_capacity" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="type_area">Diện tích:</label>
                <input type="text" class="form-control" id="type_area" name="type_area" value="">
                @error('type_area')
                <small id="type_area" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="facilities">Tiện nghi: </label>

                @foreach($facilities as $facility)
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="facilities[]"
                        value="{{ $facility->facility_id }}">
                    <label class="form-check-label"
                        for="facility_{{ $facility->facility_id }}">{{ $facility->facility_name }} -
                        {{ $facility->facility_description }}</label>
                </div>
                @endforeach
            </div>

            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection