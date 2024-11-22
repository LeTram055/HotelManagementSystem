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

<form method="GET" action="{{ route('admin.payment.index') }}" class="row mb-3 justify-content-center">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded" placeholder="Tìm kiếm thanh toán..."
                value="{{ request('search') }}">
            <button class="btn btn-bg rounded ms-2" type="submit">Tìm kiếm</button>
        </div>
    </div>
</form>

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.payment.create') }}" class="btn btn-primary">Thêm mới</a>
    <a href="{{ route('admin.payment.export') }}" class="btn btn-success">Xuất Excel</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">
                    <a
                        href="{{ route('admin.payment.index', ['sort_field' => 'payment_id', 'sort_direction' => $sortField == 'payment_id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        Mã thanh toán
                        @if($sortField == 'payment_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.payment.index', ['sort_field' => 'employee_name', 'sort_direction' => $sortField == 'employee_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        Nhân viên xử lý
                        @if($sortField == 'employee_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.payment.index', ['sort_field' => 'customer_name', 'sort_direction' => $sortField == 'customer_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        Khách hàng
                        @if($sortField == 'customer_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.payment.index', ['sort_field' => 'payment_date', 'sort_direction' => $sortField == 'payment_date' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        Ngày thanh toán
                        @if($sortField == 'payment_date')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.payment.index', ['sort_field' => 'payment_amount', 'sort_direction' => $sortField == 'payment_amount' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        Số tiền
                        @if($sortField == 'payment_amount')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.payment.index', ['sort_field' => 'payment_method', 'sort_direction' => $sortField == 'payment_method' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        Phương thức
                        @if($sortField == 'payment_method')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
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
                        <!-- <form class="mx-1" name=frmDelete method="post" action="{{ route('admin.payment.delete') }}">
                            @csrf
                            <input type="hidden" name="payment_id" value="{{ $payment->payment_id }}">
                            <button type="submit" class="btn btn-danger btn-sm delete-payment-btn">Xóa</button>
                        </form> -->
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal xác nhận xóa
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
</div> -->
@endsection

<!-- @section('custom-scripts')
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
@endsection -->