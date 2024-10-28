@extends('admin/layouts/master')

@section('title')
Quản lý đặt phòng
@endsection

@section('feature-title')
Quản lý đặt phòng
@endsection

@section('content')
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="btn-close"
            data-bs-dismiss="alert" aria-label="Close"></button></p>
    @endif
    @endforeach
</div>

<form method="GET" action="{{ route('admin.reservation.index') }}" class="row mb-3 justify-content-center">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded" placeholder="Tìm kiếm đặt phòng..."
                value="{{ request('search') }}">
            <button class="btn btn-bg rounded ms-2" type="submit">Tìm kiếm</button>
        </div>
    </div>
</form>

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.reservation.create') }}" class="btn btn-primary">Thêm mới</a>
    <a href="{{ route('admin.reservation.export') }}" class="btn btn-success">Xuất Excel</a>
</div>

<div class="table-responsive ">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">
                    <a
                        href="{{ route('admin.reservation.index', ['sort_field' => 'reservation_id', 'sort_direction' => $sortField == 'reservation_id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        Mã đặt phòng
                        @if($sortField == 'reservation_id')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.reservation.index', ['sort_field' => 'customer_name', 'sort_direction' => $sortField == 'customer_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        Khách hàng
                        @if($sortField == 'customer_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.reservation.index', ['sort_field' => 'type_name', 'sort_direction' => $sortField == 'type_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        Loại phòng
                        @if($sortField == 'type_name')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    Phòng
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.reservation.index', ['sort_field' => 'reservation_date', 'sort_direction' => $sortField == 'reservation_date' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        Ngày đặt
                        @if($sortField == 'reservation_date')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @else
                        <i class="fas "></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.reservation.index', ['sort_field' => 'reservation_checkin', 'sort_direction' => $sortField == 'reservation_checkin' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        Ngày nhận phòng
                        @if($sortField == 'reservation_checkin')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @else
                        <i class="fas "></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.reservation.index', ['sort_field' => 'reservation_checkout', 'sort_direction' => $sortField == 'reservation_checkout' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        Ngày trả phòng
                        @if($sortField == 'reservation_checkout')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @else
                        <i class="fas "></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a
                        href="{{ route('admin.reservation.index', ['sort_field' => 'reservation_status', 'sort_direction' => $sortField == 'reservation_status' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        Trạng thái
                        @if($sortField == 'reservation_status')
                        <i class="fas {{ $sortDirection == 'asc' ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservations as $reservation)
            <tr>
                <td class="text-center">{{ $reservation->reservation_id }}</td>
                <td>{{ $reservation->customer->customer_name }}</td>
                <td>
                    @foreach ($reservation->rooms as $room)
                    {{ $room->type->type_name }} <br>
                    @endforeach
                </td>
                <td class="text-center">
                    @foreach ($reservation->rooms as $room)
                    {{ $room->room_name }} <br>
                    @endforeach
                </td>

                <td class="text-center">{{ $reservation->reservation_date }}</td>
                <td class="text-center">{{ $reservation->reservation_checkin }}</td>
                <td class="text-center">{{ $reservation->reservation_checkout }}</td>
                <td class="text-center">{{ $reservation->reservation_status }}</td>
                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.reservation.edit', ['reservation_id' => $reservation->reservation_id]) }}"
                            class="btn btn-warning btn-sm">Sửa</a>
                        <form class="mx-1" name=frmDelete method="post"
                            action="{{ route('admin.reservation.delete') }}">
                            @csrf
                            <input type="hidden" name="reservation_id" value="{{ $reservation->reservation_id }}">
                            <button reservation="submit"
                                class="btn btn-danger btn-sm btn-sm delete-reservation-btn">Xóa</button>
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
                <button reservation="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button reservation="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button reservation="button" class="btn btn-danger" id="confirm-delete">Xóa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
$(document).ready(function() {
    let formToSubmit;

    $('.delete-reservation-btn').on('click', function(e) {
        e.preventDefault();

        formToSubmit = $(this).closest('form');
        const reservationName = $(this).closest('tr').find('td').eq(0).text();

        if (reservationName.length > 0) {
            $('.modal-body').html(`Bạn có muốn xóa đặt phòng "${reservationName}" không?`);
        }

        $('#delete-confirm').modal('show'); // Hiển thị modal
    });

    $('#confirm-delete').on('click', function() {
        formToSubmit.submit();
    });
});
</script>
@endsection