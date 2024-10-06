@extends('admin/layouts/master')

@section('title')
Quản lý đặt phòng
@endsection

@section('feature-title')
Quản lý đặt phòng
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Cập nhật đặt phòng</h3>
        <form name="frmEdit" id="frmEdit" method="post" action="{{ route('admin.reservation.update') }}">
            @csrf
            <input type="hidden" name="reservation_id" value="{{ $reservation->reservation_id }}">
            <div class="form-group">
                <label for="customer_id">Khách hàng:</label>
                <select class="form-control" id="customer_id" name="customer_id" required>
                    <option value="">Chọn khách hàng</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->customer_id }}"
                        {{ $reservation->customer_id == $customer->customer_id ? 'selected' : '' }}>
                        {{ $customer->customer_name }}
                    </option>
                    @endforeach
                </select>
                @error('customer_id')
                <small id="customer_id" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group" id="roomList">
                <label for="room_ids">Phòng trống:</label>
                <div id="room_ids" class="ms-3">
                    @foreach($rooms->groupBy('type_id') as $typeId => $roomGroup)
                    @if($roomGroup->isNotEmpty())
                    <p>{{ $roomGroup->first()->type->type_name }} (Giá: {{ $roomGroup->first()->type->type_price }} VNĐ)
                    </p>
                    <div class="d-flex flex-wrap mb-2 ms-2">
                        @foreach($roomGroup as $room)
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" name="room_ids[]"
                                value="{{ $room->room_id }}" id="room_{{ $room->room_id }}"
                                {{ in_array($room->room_id, $reservation->rooms->pluck('room_id')->toArray()) ? 'checked' : '' }}>
                            <label class="form-check-label" for="room_{{ $room->room_id }}">
                                {{ $room->room_name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @endforeach
                </div>
                @error('room_ids')
                <small id="room_ids" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="reservation_checkin">Ngày nhận phòng:</label>
                <input type="date" class="form-control" id="reservation_checkin" name="reservation_checkin"
                    value="{{ $reservation->reservation_checkin }}">
                @error('reservation_checkin')
                <small id="reservation_checkin" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="reservation_checkout">Ngày trả phòng:</label>
                <input type="date" class="form-control" id="reservation_checkout" name="reservation_checkout"
                    value="{{ $reservation->reservation_checkout }}">
                @error('reservation_checkout')
                <small id="reservation_checkout" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="reservation_status">Trạng thái:</label>
                <select class="form-control" id="reservation_status" name="reservation_status">
                    <option value="Pending" {{ $reservation->reservation_status == 'Chờ xác nhận' ? 'selected' : '' }}>
                        Chờ xác nhận</option>
                    <option value="Confirmed" {{ $reservation->reservation_status == 'Đã xác nhận' ? 'selected' : '' }}>
                        Đã xác nhận</option>
                    <option value="Checked-in"
                        {{ $reservation->reservation_status == 'Đã nhận phòng' ? 'selected' : '' }}>Đã nhận phòng
                    </option>
                    <option value="Checked-out"
                        {{ $reservation->reservation_status == 'Đã trả phòng' ? 'selected' : '' }}>Đã trả phòng</option>
                    <option value="Cancelled" {{ $reservation->reservation_status == 'Đã hủy' ? 'selected' : '' }}>Đã
                        hủy</option>
                </select>
                @error('reservation_status')
                <small id="reservation_status" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button reservation="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection