@extends('admin.layouts.master')

@section('title')
Quản lý thanh toán
@endsection

@section('feature-title')
Quản lý thanh toán
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6 border rounded-1 p-3">
        <h3 class="text-center title2">Cập nhật đặt phòng</h3>
        <form id="frmCreate" name="frmCreate" action="{{ route('admin.payment.save') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="employee_id">Nhân viên</label>
                <select class="form-control" id="employee_id" name="employee_id" required>
                    <option value="">Chọn nhân viên</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->employee_id }}">{{ $employee->employee_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="reservation_id">Đơn đặt phòng</label>
                <select class="form-control" id="reservation_id" name="reservation_id" required>
                    <option value="">Chọn đặt phòng</option>
                    @foreach($reservations as $reservation)
                    <option value="{{ $reservation->reservation_id }}"
                        data-checkin="{{ $reservation->reservation_checkin }}"
                        data-checkout="{{ $reservation->reservation_checkout }}"
                        data-rooms='@json($reservation->rooms)'>Mã: {{ $reservation->reservation_id }} -
                        Khách:
                        {{ $reservation->customer->customer_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="payment_price">Số tiền thanh toán</label>
                <input type="text" class="form-control" id="payment_price" name="payment_price" readonly>
            </div>

            <div class="form-group">
                <label for="payment_method">Phương thức thanh toán</label>
                <select class="form-control" id="payment_method" name="payment_method" required>
                    <option value="Tiền mặt">Tiền mặt</option>
                    <option value="Chuyển khoản">Chuyển khoản</option>
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary my-2">Lưu</button>
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
$(document).ready(function() {
    $('#reservation_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        const roomsData = selectedOption.data('rooms');

        const checkin = new Date(selectedOption.data('checkin'));
        const checkout = new Date(selectedOption.data('checkout'));
        const days = (checkout - checkin) / (1000 * 60 * 60 * 24); // Tính số ngày


        const rooms = roomsData; // Sử dụng trực tiếp roomsData
        let totalPrice = 0;
        rooms.forEach(room => {

            totalPrice += room.type.type_price * days;

        });

        $('#payment_price').val(totalPrice + ' VND');

    });
});
document.getElementById('frmCreate').addEventListener('submit', function(e) {
    console.log('Form is being submitted');
});
</script>
@endsection