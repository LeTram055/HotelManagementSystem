@extends('admin/layouts/master')

@section('title')
Quản lý trạng thái phòng
@endsection

@section('feature-title')
Quản lý trạng thái phòng
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Thêm mới trạng thái phòng</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.roomstatus.save') }}">
            @csrf

            <div class="form-group">
                <label for="status_name">Tên trạng thái:</label>
                <input type="text" class="form-control" id="status_name" name="status_name" value="">
                @error('status_name')
                <small id="status_name" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection