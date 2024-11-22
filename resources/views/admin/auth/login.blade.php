@extends('admin/layouts/master')

@section('title')
Đăng nhập
@endsection

<!-- @section('feature-title')
Đăng nhập
@endsection -->

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="btn-close"
            data-bs-dismiss="alert" aria-label="Close"></p>
    @endif
    @endforeach
</div>
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Đăng nhập</h3>
        <form name="login" id="frmLogin" action="{{ route('login.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="account_username">Tên đăng nhập:</label>
                <input class="form-control" type="text" name="account_username" id="account_username" required>
                @error('account_username')
                <small id="account_username" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="account_password">Mật khẩu:</label>
                <input class="form-control" type="password" name="account_password" id="account_password" required>
                @error('account_password')
                <small id="account_password" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>


            <div class="form-group text-center">
                <button type="submit" name="submit" class="btn btn-primary my-2">Đăng nhập</button>
            </div>
            @if ($errors->has('login_error'))
            <p style="color: red;">{{ $errors->first('login_error') }}</p>
            @endif
        </form>
    </div>
</div>
@endsection