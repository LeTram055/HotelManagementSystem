@extends('admin/layouts/master')

@section('title')
Quản lý nhân viên
@endsection

@section('feature-title')
Quản lý nhân viên
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Thêm mới nhân viên</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.employee.save') }}">
            @csrf

            <div class="form-group">
                <label for="employee_name">Tên nhân viên:</label>
                <input type="text" class="form-control" id="employee_name" name="employee_name" value="">
                @error('employee_name')
                <small id="employee_name" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="employee_phone">Số điện thoại:</label>
                <input type="text" class="form-control" id="employee_phone" name="employee_phone" value="">
                @error('employee_phone')
                <small id="employee_phone" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="employee_email">Email:</label>
                <input type="text" class="form-control" id="employee_email" name="employee_email" value="">
                @error('employee_email')
                <small id="employee_email" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="employee_address">Địa chỉ:</label>
                <textarea rows="2" type="text" class="form-control" id="employee_address" name="employee_address">
                </textarea>
                @error('employee_address')
                <small id="employee_address" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="employee_status">Tình trạng:</label>
                <input type="text" class="form-control" id="employee_status" name="employee_status" value="">
                @error('employee_status')
                <small id="employee_status" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection