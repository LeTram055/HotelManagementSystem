@extends('admin/layouts/master')

@section('title')
Quản lý nhân viên
@endsection

@section('feature-title')
Quản lý nhân viên
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

<form method="GET" action="{{ route('admin.employee.index') }}" class="row mb-3 justify-content-center">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded" placeholder="Tìm kiếm khách hàng..."
                value="{{ request('search') }}">
            <button class="btn btn-bg rounded ms-2" type="submit">Tìm kiếm</button>
        </div>
    </div>
</form>

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.employee.create') }}" class="btn btn-primary">Thêm mới</a>
    <a href="{{ route('admin.employee.export') }}" class="btn btn-success">Xuất Excel</a>
</div>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center"><a
                        href="{{ route('admin.employee.index', ['sort_field' => 'employee_id', 'sort_direction' => $sortField == 'employee_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã nhân viên
                        @if($sortField == 'employee_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center"><a
                        href="{{ route('admin.employee.index', ['sort_field' => 'employee_name', 'sort_direction' => $sortField == 'employee_name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tên nhân viên
                        @if($sortField == 'employee_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center"><a
                        href="{{ route('admin.employee.index', ['sort_field' => 'employee_phone', 'sort_direction' => $sortField == 'employee_phone' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Số điện thoại
                        @if($sortField == 'employee_phone')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a></th>
                <th class="text-center"><a
                        href="{{ route('admin.employee.index', ['sort_field' => 'employee_email', 'sort_direction' => $sortField == 'employee_email' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Email
                        @if($sortField == 'employee_email')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center"><a
                        href="{{ route('admin.employee.index', ['sort_field' => 'employee_address', 'sort_direction' => $sortField == 'employee_address' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Địa chỉ
                        @if($sortField == 'employee_address')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a></th>
                <th class="text-center"><a
                        href="{{ route('admin.employee.index', ['sort_field' => 'employee_status', 'sort_direction' => $sortField == 'employee_status' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tình trạng
                        @if($sortField == 'employee_status')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a></th>
                <th class="text-center"><a
                        href="{{ route('admin.employee.index', ['sort_field' => 'account_id', 'sort_direction' => $sortField == 'account_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã tài khoản
                        @if($sortField == 'account_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a></th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
            <tr>
                <td class="text-center">{{ $employee->employee_id }}</td>
                <td>{{ $employee->employee_name }}</td>
                <td>{{ $employee->employee_phone}}</td>
                <td>{{ $employee->employee_email}}</td>
                <td>{{ $employee->employee_address}}</td>
                <td>{{ $employee->employee_status}}</td>
                <td class="text-center">{{ $employee->account_id }}</td>
                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.employee.edit', ['employee_id' => $employee->employee_id]) }}"
                            class="btn btn-warning btn-sm">Sửa</a>
                        <form class="mx-1" name=frmDelete method="post" action="{{ route('admin.employee.delete') }}">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">
                            <button employee="submit"
                                class="btn btn-danger btn-sm btn-sm delete-employee-btn">Xóa</button>
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
                <button employee="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button employee="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button employee="button" class="btn btn-danger" id="confirm-delete">Xóa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
$(document).ready(function() {
    let formToSubmit;

    $('.delete-employee-btn').on('click', function(e) {
        e.preventDefault();

        formToSubmit = $(this).closest('form');
        const employeeName = $(this).closest('tr').find('td').eq(1).text();

        if (employeeName.length > 0) {
            $('.modal-body').html(`Bạn có muốn xóa nhân viên "${employeeName}" không?`);
        }

        $('#delete-confirm').modal('show'); // Hiển thị modal
    });

    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });
});
</script>
@endsection