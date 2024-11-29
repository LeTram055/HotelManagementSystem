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
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="btn-close"
            data-bs-dismiss="alert" aria-label="Close"></p>
    @endif
    @endforeach
</div>

<form method="GET" action="{{ route('admin.account.index') }}" class="row mb-3 justify-content-center">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded" placeholder="Tìm kiếm nhân viên..."
                value="{{ request('search') }}">
            <button class="btn btn-bg rounded ms-2" type="submit">Tìm kiếm</button>
        </div>
    </div>
</form>

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.account.create') }}" class="btn btn-primary">Thêm mới</a>
    <a href="{{ route('admin.account.export') }}" class="btn btn-success">Xuất Excel</a>
</div>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center"><a
                        href="{{ route('admin.account.index', ['sort_field' => 'account_id', 'sort_direction' => $sortField == 'account_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã tài khoản
                        @if($sortField == 'account_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center"><a
                        href="{{ route('admin.account.index', ['sort_field' => 'account_username', 'sort_direction' => $sortField == 'account_username' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tên tài khoản
                        @if($sortField == 'account_username')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>

                <th class="text-center"><a
                        href="{{ route('admin.account.index', ['sort_field' => 'account_role', 'sort_direction' => $sortField == 'account_role' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Vai trò
                        @if($sortField == 'account_role')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center"><a
                        href="{{ route('admin.account.index', ['sort_field' => 'account_active', 'sort_direction' => $sortField == 'account_active' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tình trạng hoạt động
                        @if($sortField == 'account_active')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accounts as $account)
            <tr>
                <td class="text-center">{{ $account->account_id }}</td>
                <td>{{ $account->account_username }}</td>

                <td class="text-center">{{ $account->account_role}}</td>
                <td class="text-center">{{ $account->account_active }}</td>
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