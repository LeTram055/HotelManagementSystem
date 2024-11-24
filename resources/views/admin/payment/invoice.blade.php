<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn thanh toán</title>
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border: none;
    }

    .text-left {
        text-align: left;
    }

    .text-center {
        text-align: center;
    }

    .header-container {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo {
        max-height: 100px;
    }

    .invoice-title {
        font-size: 24px;
        color: #333;
    }

    .customer-info,
    .payment-info,
    .order-info {
        margin-top: 20px;
    }

    .customer-info p,
    .payment-info p {
        margin: 5px 0;
    }

    .customer-info {
        text-align: center;
    }

    /* New style for the payment and reservation tables */
    .payment-reservation-table {
        width: 100%;
        border: 1px solid #ccc;
        margin-top: 20px;
    }

    .payment-reservation-table td {
        vertical-align: top;
        padding: 10px;
    }

    .payment-reservation-table th {
        padding: 8px;
    }

    .order-details-table {

        width: 100%;
        border: 1px solid #ccc;
        margin-top: 20px;

    }
    </style>
</head>

<body>
    <div class="header-container">
        <h3>Ánh Dương Hotel</h3>
        <p>Địa chỉ: 3/2, Xuân Khánh, Ninh Kiều, Cần Thơ</p>
        <h1 class="invoice-title">HÓA ĐƠN THANH TOÁN</h1>
        <p>Ngày thanh toán: {{ $payment->payment_date }}</p>
    </div>

    <div class="customer-info">
        <h3>Thông tin khách hàng</h3>
        <p>Tên khách hàng: {{ $customer->customer_name }}</p>
        <p>CCCD: {{ $customer->customer_cccd ?? '' }}</p>
        <p>Email: {{ $customer->customer_email ?? '' }}</p>
    </div>

    <!-- Payment and Reservation Information in a table format -->
    <table class="payment-reservation-table">
        <tr>
            <td style="width: 50%; padding-right: 20px;">
                <h3 class="text-center">Thông tin thanh toán</h3>
                <table class="info-table">
                    <tr>
                        <th>Mã thanh toán:</th>
                        <td>{{ $payment->payment_id }}</td>
                    </tr>
                    <tr>
                        <th>Số tiền:</th>
                        <td>{{ $formatted_price }} VND</td>
                    </tr>
                    <tr>
                        <th>Phương thức:</th>
                        <td>{{ $payment->payment_method }}</td>
                    </tr>
                    <tr>
                        <th>Nhân viên xử lý:</th>
                        <td>{{ $employee->employee_name }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; padding-left: 20px;">
                <h3 class="text-center">Thông tin đơn đặt phòng</h3>
                <table class="info-table">
                    <tr>
                        <th>Mã đơn đặt phòng:</th>
                        <td>{{ $payment->reservation->reservation_id }}</td>
                    </tr>
                    <tr>
                        <th>Ngày đặt:</th>
                        <td>{{ $payment->reservation->reservation_date }}</td>
                    </tr>
                    <tr>
                        <th>Ngày nhận:</th>
                        <td>{{ $payment->reservation->reservation_checkin }}</td>
                    </tr>
                    <tr>
                        <th>Ngày trả:</th>
                        <td>{{ $payment->reservation->reservation_checkout }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="order-details">
        <h3 class="text-center">Chi tiết phòng đã đặt</h3>
        <table class="order-details-table">
            <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th class=" text-center">Số phòng</th>
                    <th class="text-center">Loại phòng</th>
                    <th class="text-center">Giá phòng (VND)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payment->reservation->rooms as $index => $room)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $room->room_name }}</td>
                    <td class="text-center">{{ $room->type->type_name }}</td>
                    <td class="text-center">{{ number_format($room->type->type_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>