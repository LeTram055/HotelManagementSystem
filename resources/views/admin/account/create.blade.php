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
        <h3 class="text-center title2">Thêm mới tài khoản</h3>
        <form name="frmEdit" id="frmEdit" method="post" action="{{ route('admin.account.save') }}">
            @csrf

            <div class="form-group">
                <label for="account_username">Tên tài khoản:</label>
                <input type="text" class="form-control" id="account_username" name="account_username" value="">
                @error('account_username')
                <small id="account_username" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="account_password">Mật khẩu:</label>
                <input type="password" class="form-control" id="account_password" name="account_password" value="">
                @error('account_password')
                <small id="account_password" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="account_role">Vai trò:</label>
                <select class="form-control" id="account_role" name="account_role" required>
                    <option value="">Chọn vai trò</option>
                    <option value="employee">Nhân viên</option>
                    <option value="customer">Khách hàng</option>
                </select>
            </div>
            <div class="form-group" id="userList" style="display:none;">
                <div id="data-container" data-employees='@json($employees)' data-customers='@json($customers)'></div>
                <label for="user_id">Chọn người dùng:</label>
                <select class="form-control" id="user_id" name="user_id" required>
                    <!-- Dữ liệu sẽ được điền bằng JavaScript khi chọn vai trò-->
                </select>
            </div>
            <div class="form-group">
                <label for="account_active">Trạng thái:</label>
                <select class="form-control" id="account_active" name="account_active" required>
                    <option value="active">active</option>
                    <option value="locked">locked</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
// Dữ liệu danh sách nhân viên và khách hàng
$(document).ready(function() {
    const dataContainer = $('#data-container');
    const employees = JSON.parse(dataContainer.attr('data-employees'));
    const customers = JSON.parse(dataContainer.attr('data-customers'));

    console.log(employees, customers);

    $('#account_role').on('change', function() {
        const role = $(this).val();
        const userList = $('#userList');
        const userSelect = $('#user_id');

        userSelect.empty(); // Xóa danh sách cũ

        if (role === 'employee') {
            $.each(employees, function(index, employee) {
                userSelect.append(
                    `<option value="${employee.employee_id}">${employee.employee_name}</option>`
                );
            });
            userList.show();
        } else if (role === 'customer') {
            $.each(customers, function(index, customer) {
                userSelect.append(
                    `<option value="${customer.customer_id}">${customer.customer_name}</option>`
                );
            });
            userList.show();
        } else {
            userList.hide();
        }
    });
});
</script>
@endsection