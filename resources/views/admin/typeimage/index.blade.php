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
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert"
            aria-label="close">&times;</a></p>
    @endif
    @endforeach
</div>

<a href="{{ route('admin.typeimage.create') }}" class="btn btn-primary mb-3">Thêm mới</a>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">Mã hình ảnh</th>
                <th class="text-center">Loại phòng</th>
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