@extends('admin/layouts/master')

@section('title')
Quản lý phòng
@endsection

@section('feature-title')
Quản lý phòng
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Thêm mới</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.room.save') }}">
            @csrf

            <div class="form-group">
                <label for="room_name">Tên phòng:</label>
                <input type="text" class="form-control" id="room_name" name="room_name" value="">
                @error('room_name')
                <small id="room_name" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="type_name">Loại phòng:</label>
                <select class="form-control" id="type_id" name="type_id">
                    @foreach ($types as $type)
                    <option value="{{ $type->type_id }}">{{ $type->type_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="status_name">Trạng thái phòng:</label>
                <select class="form-control" id="status_id" name="status_id">
                    @foreach ($roomStatuses as $roomStatus)
                    <option value="{{ $roomStatus->status_id }}">{{ $roomStatus->status_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="room_note">Ghi chú:</label>
                <input room="text" class="form-control" id="room_note" name="room_note" value="">
                @error('room_note')
                <small id="room_note" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection