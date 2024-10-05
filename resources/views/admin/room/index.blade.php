@extends('admin/layouts/master')

@section('title')
Quản lý phòng
@endsection

@section('feature-title')
Quản lý phòng
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

<a href="{{ route('admin.room.create') }}" class="btn btn-primary mb-3">Thêm mới sản phẩm</a>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">Mã phòng</th>
                <th class="text-center">Tên phòng</th>
                <th class="text-center">Loại phòng</th>
                <th class="text-center">Tình trạng phòng</th>
                <th class="text-center">Ghi chú</th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rooms as $room)
            <tr>
                <td class="text-center">{{ $room->room_id }}</td>
                <td class="text-center">{{ $room->room_name }}</td>
                <td>{{ $room->type->type_name }}</td>
                <td>{{ $room->status->status_name }}</td>
                <td>{{ $room->room_note }}</td>
                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.room.edit', ['room_id' => $room->room_id]) }}"
                            class="btn btn-warning btn-sm">Sửa</a>
                        <form class="mx-1" name=frmDelete method="post" action="{{ route('admin.room.delete') }}">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->room_id }}">
                            <button type="submit" class="btn btn-danger btn-sm btn-sm delete-room-btn">Xóa</button>
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

    $('.delete-room-btn').on('click', function(e) {
        e.preventDefault();

        formToSubmit = $(this).closest('form');
        const roomName = $(this).closest('tr').find('td').eq(1).text();

        if (roomName.length > 0) {
            $('.modal-body').html(`Bạn có muốn xóa phòng "${roomName}" không?`);
        }

        $('#delete-confirm').modal('show'); // Hiển thị modal
    });

    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });
});
</script>
@endsection