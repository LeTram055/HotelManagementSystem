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
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="btn-close"
            data-bs-dismiss="alert" aria-label="Close"></p>
    @endif
    @endforeach
</div>

<form method="GET" action="{{ route('admin.customer.index') }}" class="row mb-3 justify-content-center">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded" placeholder="Tìm kiếm khách hàng..."
                value="{{ request('search') }}">
            <button class="btn btn-bg rounded ms-2" type="submit">Tìm kiếm</button>
        </div>
    </div>
</form>



<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.customer.create') }}" class="btn btn-primary">Thêm mới</a>
    <a href="{{ route('admin.customer.export') }}" class="btn btn-success">Xuất Excel</a>
</div>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center"><a
                        href="{{ route('admin.customer.index', ['sort_field' => 'customer_id', 'sort_direction' => $sortField == 'customer_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã khách hàng
                        @if($sortField == 'customer_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center"><a
                        href="{{ route('admin.customer.index', ['sort_field' => 'customer_name', 'sort_direction' => $sortField == 'customer_name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tên khách hàng
                        @if($sortField == 'customer_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center"><a
                        href="{{ route('admin.customer.index', ['sort_field' => 'customer_cccd', 'sort_direction' => $sortField == 'customer_cccd' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Căn cước công dân
                        @if($sortField == 'customer_cccd')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center"><a
                        href="{{ route('admin.customer.index', ['sort_field' => 'customer_email', 'sort_direction' => $sortField == 'customer_email' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Email
                        @if($sortField == 'customer_email')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center"><a
                        href="{{ route('admin.customer.index', ['sort_field' => 'customer_address', 'sort_direction' => $sortField == 'customer_address' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Địa chỉ
                        @if($sortField == 'customer_address')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center"><a
                        href="{{ route('admin.customer.index', ['sort_field' => 'account_id', 'sort_direction' => $sortField == 'account_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã tài khoản
                        @if($sortField == 'account_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $customer)
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
            @empty
            <tr>
                <td colspan="7" class="text-center">Không tìm thấy khách hàng</td>
            </tr>
            @endforelse

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