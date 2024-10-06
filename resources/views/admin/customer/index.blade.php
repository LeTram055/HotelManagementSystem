@extends('admin/layouts/master')

@section('title')
Quản lý khách hàng
@endsection

@section('feature-title')
Quản lý khách hàng
@endsection

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert"
            aria-label="close">&times;</a></p>
    @endif
    @endforeach
</div>

<a href="{{ route('admin.customer.create') }}" class="btn btn-primary mb-3">Thêm mới</a>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">Mã khách hàng</th>
                <th class="text-center">Tên khách hàng</th>
                <th class="text-center">Căn cước công dân</th>
                <th class="text-center">Email</th>
                <th class="text-center">Địa chỉ</th>
                <th class="text-center">Mã tài khoản</th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
            <tr>
                <td class="text-center">{{ $customer->customer_id }}</td>
                <td>{{ $customer->customer_name }}</td>
                <td>{{ $customer->customer_cccd}}</td>
                <td>{{ $customer->customer_email}}</td>
                <td>{{ $customer->customer_address}}</td>
                <td class="text-center">{{ $customer->account_id }}</td>
                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.customer.edit', ['customer_id' => $customer->customer_id]) }}"
                            class="btn btn-warning btn-sm">Sửa</a>
                        <form class="mx-1" name=frmDelete method="post" action="{{ route('admin.customer.delete') }}">
                            @csrf
                            <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">
                            <button customer="submit"
                                class="btn btn-danger btn-sm btn-sm delete-customer-btn">Xóa</button>
                        </form>
                    </div>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="delete-confirm" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmLabel">Xác nhận xóa</h5>
                <button customer="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button customer="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button customer="button" class="btn btn-danger" id="confirm-delete">Xóa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
$(document).ready(function() {
    let formToSubmit;

    $('.delete-customer-btn').on('click', function(e) {
        e.preventDefault();

        formToSubmit = $(this).closest('form');
        const customerName = $(this).closest('tr').find('td').eq(1).text();

        if (customerName.length > 0) {
            $('.modal-body').html(`Bạn có muốn xóa khách hàng "${customerName}" không?`);
        }

        $('#delete-confirm').modal('show'); // Hiển thị modal
    });

    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });
});
</script>
@endsection