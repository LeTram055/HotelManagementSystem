@extends('admin.layouts.master')

@section('title')
Quản lý thanh toán
@endsection

@section('feature-title')
Quản lý thanh toán
@endsection

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="btn-close"
            data-bs-dismiss="alert" aria-label="Close"></p>
    @endif
    @endforeach
</div>

<a href="{{ route('admin.payment.create') }}" class="btn btn-primary mb-3">Thêm mới</a>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">Mã thanh toán</th>
                <th class="text-center">Nhân viên xử lý</th>
                <th class="text-center">Khách hàng</th>
                <th class="text-center">Ngày thanh toán</th>
                <th class="text-center">Số tiền (VNĐ)</th>
                <th class="text-center">Phương thức</th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
            <tr>
                <td class="text-center">{{ $payment->payment_id }}</td>
                <td class="text-center">{{ $payment->employee->employee_name ?? 'N/A' }}</td>
                <td class="text-center">{{ $payment->reservation->customer->customer_name ?? 'N/A' }}</td>
                <td class="text-center">{{ $payment->payment_date }}</td>
                <td class="text-center">{{ number_format($payment->payment_price, 0, ',', '.') }}</td>
                <td class="text-center">{{ $payment->payment_method }}</td>
                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.payment.edit', ['payment_id' => $payment->payment_id]) }}"
                            class="btn btn-warning btn-sm">Sửa</a>
                        <form class="mx-1" name=frmDelete method="post" action="{{ route('admin.payment.delete') }}">
                            @csrf
                            <input type="hidden" name="payment_id" value="{{ $payment->payment_id }}">
                            <button type="submit" class="btn btn-danger btn-sm delete-payment-btn">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="delete-confirm" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Xóa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
$(document).ready(function() {
    let formToSubmit;

    $('.delete-payment-btn').on('click', function(e) {
        e.preventDefault();

        formToSubmit = $(this).closest('form');
        const paymentId = $(this).closest('tr').find('td').eq(0).text();

        if (paymentId.length > 0) {
            $('.modal-body').html(`Bạn có muốn xóa thanh toán mã "${paymentId}" không?`);
        }

        $('#delete-confirm').modal('show'); // Hiển thị modal
    });

    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });
});
</script>
@endsection