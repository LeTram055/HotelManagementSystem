@extends('admin/layouts/master')

@section('title')
Đổi mật khẩu
@endsection

@section('feature-title')
Đổi mật khẩu
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Đổi mật khẩu</h3>
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="current_password">Mật khẩu hiện tại</label>
                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                    id="current_password" name="current_password" required>
                @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="new_password">Mật khẩu mới</label>
                <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                    id="new_password" name="new_password" required>
                @error('new_password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                <input type="password" class="form-control" id="new_password_confirmation"
                    name="new_password_confirmation" required>
            </div>
            <div class="form-group text-center">
                <button type="submit" name="submit" class="btn btn-primary my-2">Đổi mật khẩu</button>
            </div>
        </form>
    </div>
    @endsection