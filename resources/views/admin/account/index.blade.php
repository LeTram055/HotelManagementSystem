@extends('admin/layouts/master')

@section('title')
Quản lý tài khoản
@endsection

@section('feature-title')
Quản lý tài khoản
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

<a href="{{ route('admin.account.create') }}" class="btn btn-primary mb-3">Thêm mới</a>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">Mã tài khoản</th>
                <th class="text-center">Tên tài khoản</th>
                <th class="text-center">Email</th>
                <th class="text-center">Mật khẩu</th>
                <th class="text-center">Vai trò</th>
                <th class="text-center">Tình trạng hoạt động</th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accounts as $account)
            <tr>
                <td class="text-center">{{ $account->account_id }}</td>
                <td>{{ $account->account_username }}</td>
                <td>{{ $account->account_email}}</td>
                <td>{{ $account->account_password}}</td>
                <td>{{ $account->account_role}}</td>
                <td>{{ $account->account_active }}</td>
                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.account.edit', ['account_id' => $account->account_id]) }}"
                            class="btn btn-warning btn-sm">Sửa</a>
                        <form class="mx-1" name=frmDelete method="post" action="{{ route('admin.account.delete') }}">
                            @csrf
                            <input type="hidden" name="account_id" value="{{ $account->account_id }}">
                            <button account="submit"
                                class="btn btn-danger btn-sm btn-sm delete-account-btn">Xóa</button>
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
                <button account="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button account="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button account="button" class="btn btn-danger" id="confirm-delete">Xóa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
$(document).ready(function() {
    let formToSubmit;

    $('.delete-account-btn').on('click', function(e) {
        e.preventDefault();

        formToSubmit = $(this).closest('form');
        const accountName = $(this).closest('tr').find('td').eq(1).text();

        if (accountName.length > 0) {
            $('.modal-body').html(`Bạn có muốn xóa tài khoản "${accountName}" không?`);
        }

        $('#delete-confirm').modal('show'); // Hiển thị modal
    });

    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });
});
</script>
@endsection