@extends('admin/layouts/master')

@section('title')
Quản lý hình ảnh loại phòng
@endsection

@section('feature-title')
Quản lý hình ảnh loại phòng
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

<form method="GET" action="{{ route('admin.typeimage.index') }}" class="row mb-3 justify-content-center">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded" placeholder="Tìm kiếm thông tin hình ảnh..."
                value="{{ request('search') }}">
            <button class="btn btn-bg rounded ms-2" type="submit">Tìm kiếm</button>
        </div>
    </div>
</form>

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.typeimage.create') }}" class="btn btn-primary">Thêm mới</a>
    <a href="{{ route('admin.typeimage.export') }}" class="btn btn-success">Xuất Excel</a>
</div>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center"><a
                        href="{{ route('admin.typeimage.index', ['sort_field' => 'image_id', 'sort_direction' => $sortField == 'image_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Mã hình ảnh
                        @if($sortField == 'image_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center"><a
                        href="{{ route('admin.typeimage.index', ['sort_field' => 'type_name', 'sort_direction' => $sortField == 'type_name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Loại phòng
                        @if($sortField == 'type_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">Hình ảnh</th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($typeImages as $typeImage)
            <tr>
                <td class="text-center">{{ $typeImage->image_id }}</td>
                <td>{{ $typeImage->type->type_name }}</td>
                <td class="text-center">

                    <img class="img-type" src="{{asset('storage/uploads/'. $typeImage->image_url) }}">
                    <br />
                    <a href="{{asset('storage/uploads/'. $typeImage->image_url) }}">{{ $typeImage->image_url }}</a>

                </td>
                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.typeimage.edit', ['image_id' => $typeImage->image_id]) }}"
                            class="btn btn-warning btn-sm">Sửa</a>
                        <form class="mx-1" name=frmDelete method="post" action="{{ route('admin.typeimage.delete') }}">
                            @csrf
                            <input type="hidden" name="image_id" value="{{ $typeImage->image_id }}">
                            <button type="submit" class="btn btn-danger btn-sm btn-sm delete-typeimage-btn">Xóa</button>
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

    $('.delete-typeimage-btn').on('click', function(e) {
        e.preventDefault();

        formToSubmit = $(this).closest('form');
        const typeImageName = $(this).closest('tr').find('td').eq(2).text();

        if (typeImageName.length > 0) {
            $('.modal-body').html(`Bạn có muốn xóa hình ảnh "${typeImageName}" không?`);
        }

        $('#delete-confirm').modal('show'); // Hiển thị modal
    });

    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });
});
</script>
@endsection