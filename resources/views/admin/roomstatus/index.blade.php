@extends('admin/layouts/master')

@section('title')
Quản lý trạng thái phòng
@endsection

@section('feature-title')
Quản lý trạng thái phòng
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

<a href="{{ route('admin.roomstatus.create') }}" class="btn btn-primary mb-3">Thêm mới sản phẩm</a>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">Mã</th>
                <th class="text-center">Tên</th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roomStatuses as $roomStatus)
            <tr>
                <td class="text-center">{{ $roomStatus->status_id }}</td>
                <td>{{ $roomStatus->status_name }}</td>
                <td class="d-flex justify-content-center">
                    <div class="d-flex">
                        <a href="{{ route('admin.roomstatus.edit', ['status_id' => $roomStatus->status_id]) }}"
                            class="btn btn-warning btn-sm">Sửa</a>
                        <form class="mx-1" name=frmDelete method="post" action="{{ route('admin.roomstatus.delete') }}">
                            @csrf
                            <input type="hidden" name="status_id" value="{{ $roomStatus->status_id }}">
                            <button type="submit"
                                class="btn btn-danger btn-sm btn-sm delete-roomstatus-btn">Xóa</button>
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

    $('.delete-roomstatus-btn').on('click', function(e) {
        e.preventDefault();

        formToSubmit = $(this).closest('form');
        const roomStatusName = $(this).closest('tr').find('td').eq(1).text();

        if (roomStatusName.length > 0) {
            $('.modal-body').html(`Bạn có muốn xóa trạng thái phòng "${roomStatusName}" không?`);
        }

        $('#delete-confirm').modal('show'); // Hiển thị modal
    });

    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });
});
</script>
@endsection