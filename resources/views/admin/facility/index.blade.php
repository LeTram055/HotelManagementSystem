@extends('admin/layouts/master')

@section('title')
Quản lý thiết bị
@endsection

@section('feature-title')
Quản lý thiết bị
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

<a href="{{ route('admin.facility.create') }}" class="btn btn-primary mb-3">Thêm mới</a>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">Mã thiết bị</th>
                <th class="text-center">Tên thiết bị</th>
                <th class="text-center">Mô tả thiết bị</th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facilities as $facility)
            <tr>
                <td class="text-center">{{ $facility->facility_id }}</td>
                <td>{{ $facility->facility_name }}</td>
                <td>{{ $facility->facility_description }}</td>
                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.facility.edit', ['facility_id' => $facility->facility_id]) }}"
                            class="btn btn-warning btn-sm">Sửa</a>
                        <form class="mx-1" name=frmDelete method="post" action="{{ route('admin.facility.delete') }}">
                            @csrf
                            <input type="hidden" name="facility_id" value="{{ $facility->facility_id }}">
                            <button type="submit" class="btn btn-danger btn-sm btn-sm delete-facility-btn">Xóa</button>
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

    $('.delete-facility-btn').on('click', function(e) {
        e.preventDefault();

        formToSubmit = $(this).closest('form');
        const facilityName = $(this).closest('tr').find('td').eq(1).text();

        if (facilityName.length > 0) {
            $('.modal-body').html(`Bạn có muốn xóa thiết bị "${facilityName}" không?`);
        }

        $('#delete-confirm').modal('show'); // Hiển thị modal
    });

    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });
});
</script>
@endsection