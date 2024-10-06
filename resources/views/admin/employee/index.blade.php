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
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert"
            aria-label="close">&times;</a></p>
    @endif
    @endforeach
</div>

<a href="{{ route('admin.employee.create') }}" class="btn btn-primary mb-3">Thêm mới</a>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">Mã nhân viên</th>
                <th class="text-center">Tên nhân viên</th>
                <th class="text-center">Số điện thoại</th>
                <th class="text-center">Email</th>
                <th class="text-center">Địa chỉ</th>
                <th class="text-center">Tình trạng</th>
                <th class="text-center">Mã tài khoản</th>
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