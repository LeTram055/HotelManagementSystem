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
    <table class="table table-sm">
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
            @foreach ($reservations->unique('reservation_id') as $reservation)

            @php

            $rowClass = '';
            $today = now()->toDateString();

            if ($reservation->reservation_status === 'Chờ xác nhận')
            {
            $rowClass = 'table-info';
            }

            if ($reservation->reservation_checkin <= $today && $reservation->reservation_status === 'Đã xác nhận')
                {
                $rowClass='table-success';
                if ($reservation->reservation_checkout <= $today && $reservation->reservation_status === 'Đã xác nhận')
                    {
                    $rowClass = 'table-danger';
                    }
                    }
                    if ($reservation->reservation_status === 'Đã nhận phòng' && $reservation->reservation_checkout <=
                        $today) { $rowClass='table-warning' ; } @endphp <tr class="{{ $rowClass }}">
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

                            </div>

                        </td>
                        </tr>
                        @endforeach
        </tbody>
    </table>
</div>
@endsection