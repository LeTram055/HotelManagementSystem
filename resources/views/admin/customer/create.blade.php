@extends('admin/layouts/master')

@section('title')
Quản lý khách hàng
@endsection

@section('feature-title')
Quản lý khách hàng
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Thêm mới khách hàng</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.customer.save') }}">
            @csrf

            <div class="form-group">
                <label for="customer_name">Tên khách hàng:</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" value="">
                @error('customer_name')
                <small id="customer_name" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="customer_phone">Số điện thoại:</label>
                <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="">
                @error('customer_phone')
                <small id="customer_phone" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="customer_address">Địa chỉ:</label>
                <textarea rows="2" type="text" class="form-control" id="customer_address" name="customer_address">
                </textarea>
                @error('customer_address')
                <small id="customer_address" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection