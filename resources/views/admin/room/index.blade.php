@extends('admin/layouts/master')

@section('title')
Quản lý phòng
@endsection

@section('feature-title')
Quản lý phòng
@endsection

@section('content')
<div class="container">
    @if(Auth::user() && Auth::user()->account_role == 'admin')
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('admin.room.create') }}" class="btn btn-primary">Thêm mới</a>
        <a href="{{ route('admin.room.export') }}" class="btn btn-success">Xuất Excel</a>
    </div>
    @endif
    <!-- Form chọn khoảng ngày và bộ lọc -->
    <form action="{{ route('admin.room.index') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="start_date">Ngày bắt đầu:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate ?? '' }}"
                    required>
            </div>
            <div class="col-md-3">
                <label for="end_date">Ngày kết thúc:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate ?? '' }}"
                    required>
                @error('end_date')
                <small id="end_date" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-3">
                <label for="room_type">Loại phòng:</label>
                <select id="room_type" name="room_type" class="form-control">
                    <option value="">Tất cả</option>
                    @foreach ($roomTypes as $type)
                    <option value="{{ $type->type_id }}" {{ $type->type_id == $roomType ? 'selected' : '' }}>
                        {{ $type->type_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="room_status">Trạng thái phòng:</label>
                <select id="room_status" name="room_status" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="Trống" {{ $roomStatus == 'Trống' ? 'selected' : '' }}>Trống</option>
                    <option value="Đang sử dụng" {{ $roomStatus == 'Đang sử dụng' ? 'selected' : '' }}>Đang sử dụng
                    </option>
                    <option value="Đã đặt" {{ $roomStatus == 'Đã đặt' ? 'selected' : '' }}>Đã đặt</option>
                    <option value="Đang sửa" {{ $roomStatus == 'Đang sửa' ? 'selected' : '' }}>Đang sửa</option>
                </select>
            </div>
        </div>
        <!-- <div class="row">
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Xem trạng thái</button>
            </div>
        </div> -->
        <div class="row mt-3">
            <div class="col-md-9">
                <label for="room_name">Tìm kiếm theo tên phòng:</label>
                <input type="text" id="room_name" name="search" class="form-control"
                    value="{{ request()->input('search') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary mt-4">Tìm kiếm</button>
            </div>
        </div>
    </form>

    <!-- Hiển thị phòng theo từng loại -->
    @foreach ($rooms->groupBy('type.type_name') as $typeName => $typeRooms)
    <div class="mb-4">
        <h3 class="mb-3">{{ $typeName ?? 'Loại phòng chưa xác định' }}</h3>
        <div class="row">
            @foreach ($typeRooms as $room)
            @php
            // Kiểm tra trạng thái phòng
            $status = $roomStatuses[$room->room_id] ?? 'Trống'; // Nếu không có, mặc định là 'Trống'
            $bgClass = match ($status) {
            'Trống' => 'bg-success', // Màu xanh lá cây
            'Đang sử dụng' => 'bg-warning', // Màu vàng
            'Đã đặt' => 'bg-danger', // Màu đỏ
            'Đang sửa' => 'bg-secondary', // Màu xám
            default => 'bg-info', // Màu xám nhạt
            };
            @endphp
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                <div class="card text-white {{ $bgClass }} text-center">
                    <div class="card-body">
                        <h5 class="card-title">Phòng {{ $room->room_name }}</h5>
                        <p class="card-text">{{ $room->room_note }}</p>
                        <p class="badge text-dark">{{ $status }}</p>

                        @if(Auth::user() && Auth::user()->account_role == 'admin')
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('admin.room.edit', ['room_id' => $room->room_id]) }}"
                                class="btn btn-outline-light btn-sm">Sửa</a>
                            <form class="mx-1" name=frmDelete method="post" action="{{ route('admin.room.delete') }}">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->room_id }}">
                                <button type="submit"
                                    class="btn btn-outline-dark btn-sm btn-sm delete-room-btn">Xóa</button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endsection