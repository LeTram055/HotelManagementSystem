@extends('admin/layouts/master')

@section('title')
Quản lý loại phòng
@endsection

@section('feature-title')
Quản lý loại phòng
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

<form method="GET" action="{{ route('admin.type.index') }}" class="row mb-3 justify-content-center">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded" placeholder="Tìm kiếm nhân viên..."
                value="{{ request('search') }}">
            <button class="btn btn-bg rounded ms-2" type="submit">Tìm kiếm</button>
        </div>
    </div>
</form>

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.type.create') }}" class="btn btn-primary">Thêm mới</a>
    <a href="{{ route('admin.type.export') }}" class="btn btn-success">Xuất Excel</a>
</div>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center"><a
                        href="{{ route('admin.type.index', ['sort_field' => 'type_id', 'sort_direction' => $sortField == 'type_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã loại phòng
                        @if($sortField == 'type_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center"><a
                        href="{{ route('admin.type.index', ['sort_field' => 'type_name', 'sort_direction' => $sortField == 'type_name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Tên loại phòng
                        @if($sortField == 'type_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center"><a
                        href="{{ route('admin.type.index', ['sort_field' => 'type_price', 'sort_direction' => $sortField == 'type_price' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Giá (VNĐ)
                        @if($sortField == 'type_price')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a></th>
                <th class="text-center"><a
                        href="{{ route('admin.type.index', ['sort_field' => 'type_capacity', 'sort_direction' => $sortField == 'type_capacity' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Sức chứa (người)
                        @if($sortField == 'type_capacity')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a></th>
                <th class="text-center"><a
                        href="{{ route('admin.type.index', ['sort_field' => 'type_area', 'sort_direction' => $sortField == 'type_area' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Diện tích (m2)
                        @if($sortField == 'type_area')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a></th>
                <th class="text-center">Tiện nghi</th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($types as $type)
            <tr>
                <td class="text-center">{{ $type->type_id }}</td>
                <td>{{ $type->type_name }}</td>
                <td class="text-end">{{ $type->type_price }}</td>
                <td class="text-center">{{ $type->type_capacity }}</td>
                <td class="text-center">{{ $type->type_area }}</td>
                <td>
                    <ul>
                        @foreach($type->facilities as $facility)
                        <li>{{ $facility->facility_name }} - {{ $facility->facility_description }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.type.edit', ['type_id' => $type->type_id]) }}"
                            class="btn btn-warning btn-sm">Sửa</a>
                        <form class="mx-1" name=frmDelete method="post" action="{{ route('admin.type.delete') }}">
                            @csrf
                            <input type="hidden" name="type_id" value="{{ $type->type_id }}">
                            <button type="submit" class="btn btn-danger btn-sm btn-sm delete-type-btn">Xóa</button>
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

    $('.delete-type-btn').on('click', function(e) {
        e.preventDefault();

        formToSubmit = $(this).closest('form');
        const typeName = $(this).closest('tr').find('td').eq(1).text();

        if (typeName.length > 0) {
            $('.modal-body').html(`Bạn có muốn xóa loại phòng "${typeName}" không?`);
        }

        $('#delete-confirm').modal('show'); // Hiển thị modal
    });

    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });
});
</script>
@endsection