@extends('admin/layouts/master')

@section('title')
Quản lý trạng thái phòng
@endsection

@section('feature-title')
Quản lý trạng thái phòng
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Cập nhật phòng</h3>
        <form name="frmEdit" id="frmEdit" method="post" action="{{ route('admin.room.update') }}">
            @csrf
            <input type="hidden" name="room_id" value="{{ $room->room_id }}">

            <div class="form-group">
                <label for="room_name">Tên phòng:</label>
                <input type="text" class="form-control" id="room_name" name="room_name" value="{{ $room->room_name }}">
                @error('room_name')
                <small id="room_name" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="type_name">Loại phòng:</label>
                <select class="form-control" id="type_id" name="type_id">
                    @foreach ($types as $type)
                    @if($type->type_id == $room->type_id)
                    <option value="{{ $type->type_id }}" selected>{{ $type->type_name }}</option>
                    @else
                    <option value="{{ $type->type_id }}">{{ $type->type_name }}</option>
                    @endif
                    @endforeach
                </select>
            </div>

            @if ($room->status_id == 1 or $room->status_id == 4)
            <!-- Nếu trạng thái là "Trống" -->
            <div class="form-group">
                <label for="status_name">Trạng thái phòng:</label>
                <select class="form-control" id="status_id" name="status_id" required>
                    <option value="1" {{ $room->status_id == 1 ? 'selected' : '' }}>Trống</option>
                    <option value="4" {{ $room->status_id == 4 ? 'selected' : '' }}>Đang sửa</option>
                </select>
            </div>
            @endif

            <div class="form-group">
                <label for="room_note">Ghi chú:</label>
                <input room="text" class="form-control" id="room_note" name="room_note" value="{{ $room->room_note }}">
                @error('room_note')
                <small id="room_note" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection