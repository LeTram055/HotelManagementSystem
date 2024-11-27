@extends('admin.layouts.master')

@section('title')
Quản lý thanh toán
@endsection

@section('feature-title')
Quản lý thanh toán
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Cập nhật thanh toán</h3>
        <form id="frmEdit" name="frmEdit" action="{{ route('admin.payment.update') }}" method="post">
            @csrf
            <input type="hidden" name="payment_id" value="{{ $payment->payment_id }}">

            <!-- <div class="form-group">
                <label for="employee_id">Nhân viên</label>
                <select class="form-control" id="employee_id" name="employee_id" required>
                    <option value="">Chọn nhân viên</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->employee_id }}"
                        {{ $payment->employee_id == $employee->employee_id ? 'selected' : '' }}>
                        {{ $employee->employee_name }}
                    </option>
                    @endforeach
                </select>
            </div> -->

            <div class="form-group">
                <label for="payment_method">Phương thức thanh toán</label>
                <select class="form-control" id="payment_method" name="payment_method" required>
                    <option value="Tiền mặt" {{ $payment->payment_method == 'Tiền mặt' ? 'selected' : '' }}>Tiền mặt
                    </option>
                    <option value="Chuyển khoản" {{ $payment->payment_method == 'Chuyển khoản' ? 'selected' : '' }}>
                        Chuyển khoản</option>
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary my-2">Cập nhật</button>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')

@endsection