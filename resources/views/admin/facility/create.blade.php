@extends('admin/layouts/master')

@section('title')
Quản lý thiết bị
@endsection

@section('feature-title')
Quản lý thiết bị
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Thêm mới thiết bị</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.facility.save') }}">
            @csrf

            <div class="form-group">
                <label for="facility_name">Tên thiết bị:</label>
                <input type="text" class="form-control" id="facility_name" name="facility_name" value="">
                @error('facility_name')
                <small id="facility_name" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="facility_description">Mô tả thiết bị:</label>
                <textarea type="text" class="form-control" id="facility_description" name="facility_description"
                    value=""></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection