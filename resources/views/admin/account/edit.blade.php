@extends('admin/layouts/master')

@section('title')
Quản lý tài khoản
@endsection

@section('feature-title')
Quản lý tài khoản
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Cập nhật tài khoản</h3>
        <form name="frmEdit" id="frmEdit" method="post" action="{{ route('admin.account.update') }}">
            @csrf
            <input type="hidden" name="account_id" value="{{ $account->account_id }}">

            <div class="form-group">
                <label for="account_username">Tên tài khoản:</label>
                <input type="text" class="form-control" id="account_username" name="account_username"
                    value="{{ $account->account_username }}">
                @error('account_username')
                <small id="account_username" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>


            <div class="form-group">
                <label for="account_role">Vai trò:</label>
                <select class="form-control" id="account_role" name="account_role" required>
                    <option value="employee" {{ $account->account_role == 'employee' ? 'selected' : '' }}>Nhân viên
                    </option>
                    <option value="customer" {{ $account->account_role == 'customer' ? 'selected' : '' }}>Khách hàng
                    </option>
                </select>
            </div>
            <div class="form-group" id="userList" style="display:none;">
                <div id="data-container" data-employees='@json($employees)' data-customers='@json($customers)'
                    data-account='@json($account)'></div>
                <label for="user_id">Chọn người dùng:</label>
                <select class="form-control" id="user_id" name="user_id" required>
                    <!-- Dữ liệu sẽ được điền bằng JavaScript khi chọn vai trò-->
                </select>
            </div>
            <div class="form-group">
                <label for="account_active">Trạng thái:</label>
                <select class="form-control" id="account_active" name="account_active" required>
                    <option value="active" {{ $account->account_active == 'active' ? 'selected' : '' }}>active</option>
                    <option value="locked" {{ $account->account_active == 'locked' ? 'selected' : '' }}>locked</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
$(document).ready(function() {
    const dataContainer = $('#data-container');
    const employees = JSON.parse(dataContainer.attr('data-employees'));
    const customers = JSON.parse(dataContainer.attr('data-customers'));
    const account = JSON.parse(dataContainer.attr('data-account'));

    const userSelect = $('#user_id');


    const role = $('#account_role').val();

    function updateUserList(role, employees, customers, account) {
        userSelect.empty(); // Xóa danh sách cũ
        if (role === 'employee') {
            $.each(employees, function(index, employee) {
                userSelect.append(
                    `<option value="${employee.employee_id}" ${employee.account_id == account.account_id ? 'selected' : ''}>${employee.employee_name}</option>`
                );
            });
            $('#userList').show();
        } else if (role === 'customer') {
            $.each(customers, function(index, customer) {
                userSelect.append(
                    `<option value="${customer.customer_id}" ${customer.account_id == account.account_id ? 'selected' : ''}>${customer.customer_name}</option>`
                );
            });
            $('#userList').show();
        } else {
            $('#userList').hide();
        }
    }

    updateUserList(account.account_role, employees, customers, account);

    $('#account_role').on('change', function() {
        const selectedRole = $(this).val();
        updateUserList(selectedRole, employees, customers, account);
    });
});
</script>

@endsection