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
        <h3 class="text-center title2">Cập nhật thiết bị</h3>
        <form name="frmEdit" id="frmCreate" method="post" action="{{ route('admin.facility.update') }}">
            @csrf
            <input type="hidden" name="facility_id" value="{{ $facility->facility_id }}">
            <div class="form-group">
                <label for="facility_name">Tên thiết bị:</label>
                <input type="text" class="form-control" id="facility_name" name="facility_name"
                    value="{{ $facility->facility_name }}">
                @error('facility_name')
                <small id="facility_name" class="form-text text-danger">{{ $message }}</small>
                @enderror

                <label for="facility_name">Mô tả thiết bị:</label>
                <textarea rows="3" type="text" class="form-control" id="facility_description"
                    name="facility_description">{{ $facility->facility_description }}
                </textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection