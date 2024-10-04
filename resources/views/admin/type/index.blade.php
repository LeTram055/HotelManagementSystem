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
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert"
            aria-label="close">&times;</a></p>
    @endif
    @endforeach
</div>

<a href="{{ route('admin.type.create') }}" class="btn btn-primary mb-3">Thêm mới</a>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">Mã loại phòng</th>
                <th class="text-center">Tên loại phòng</th>
                <th class="text-center">Giá (VNĐ)</th>
                <th class="text-center">Sức chứa (người)</th>
                <th class="text-center">Diện tích (m2)</th>
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
                <td class="d-flex justify-content-center">
                    <div class="d-flex">
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