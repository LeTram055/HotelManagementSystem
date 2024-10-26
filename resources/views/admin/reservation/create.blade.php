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
        <h3 class="text-center title2">Thêm mới đặt phòng</h3>
        <form name="frmCreate" id="frmCreate" method="post" action="{{ route('admin.reservation.save') }}">
            @csrf

            <div class="form-group">
                <label for="customer_id">Khách hàng:</label>
                <select class="form-control" id="customer_id" name="customer_id" required>
                    <option value="">Chọn khách hàng</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->customer_id }}">{{ $customer->customer_name }}</option>
                    @endforeach
                </select>
                @error('customer_id')
                <small id="customer_id" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="reservation_checkin">Ngày nhận phòng:</label>
                <input type="date" class="form-control" id="reservation_checkin" name="reservation_checkin" value=""
                    required>
                @error('reservation_checkin')
                <small id=" reservation_checkin" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="reservation_checkout">Ngày trả phòng:</label>
                <input type="date" class="form-control" id="reservation_checkout" name="reservation_checkout" value=""
                    required>
                @error('reservation_checkout')
                <small id=" reservation_checkout" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="room_ids">Phòng trống:</label>
                <div id="room_ids" class="ms-3">
                    <!-- Danh sách phòng sẽ được cập nhật qua AJAX -->
                </div>
                @error('room_ids')
                <small id="room_ids" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>


            <button reservation="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
$(document).ready(function() {
    $('#reservation_checkin, #reservation_checkout').on('change', function() {
        const checkinDate = $('#reservation_checkin').val();
        const checkoutDate = $('#reservation_checkout').val();

        if (checkinDate && checkoutDate) {
            $.ajax({
                url: "{{ route('admin.reservation.getAvailableRooms') }}", // Thay đổi URL này cho phù hợp
                type: 'GET',
                data: {
                    checkin: checkinDate,
                    checkout: checkoutDate
                },
                success: function(data) {
                    $('#room_ids').empty(); // Xóa nội dung cũ

                    if (data.length > 0) {
                        const roomsByType = {};

                        data.forEach(room => {
                            const typeName = room.type
                                .type_name; // Lấy tên loại phòng
                            if (!roomsByType[typeName]) {
                                roomsByType[typeName] = []; // Khởi tạo nếu chưa có
                            }
                            roomsByType[typeName].push(
                                room); // Thêm phòng vào loại tương ứng
                        });

                        // Hiển thị phòng theo nhóm loại
                        for (const type in roomsByType) {
                            $('#room_ids').append(`<strong>${type}:</strong><br>`);
                            roomsByType[type].forEach(room => {
                                const roomCheckbox = `
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="room_ids[]" value="${room.room_id}" id="room_${room.room_id}">
                                        <label class="form-check-label" for="room_${room.room_id}">
                                            ${room.room_name} (Giá: ${room.type.type_price} VNĐ)
                                        </label>
                                    </div>`;
                                $('#room_ids').append(roomCheckbox);
                            });
                            $('#room_ids').append('<hr>'); // Ngăn cách giữa các loại phòng
                        }
                    } else {
                        $('#room_ids').html('<p>Không có phòng trống.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching available rooms:', error);
                }
            });
        }
    });
});
</script>
@endsection