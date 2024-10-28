@extends('admin.layouts.master')

@section('title')
Dashboard
@endsection

@section('feature-title')
Bảng điều khiển
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Tổng số phòng</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalRooms }}</h5>
                    <p class="card-text">Tổng số phòng trong khách sạn.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Phòng trống</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $availableRooms }}</h5>
                    <p class="card-text">Số phòng hiện đang trống và sẵn sàng cho thuê.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Tổng số khách hàng</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalCustomers }}</h5>
                    <p class="card-text">Tổng số khách hàng đã đăng ký.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Tổng số nhân viên</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalEmployees }}</h5>
                    <p class="card-text">Tổng số nhân viên trong khách sạn.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-header">Tổng số đặt phòng</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalReservations }}</h5>
                    <p class="card-text">Tổng số đơn đặt phòng đã được thực hiện.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Đặt phòng hôm nay</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $todayReservations }}</h5>
                    <p class="card-text">Số đơn đặt phòng được tạo trong ngày hôm nay.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Thống kê tình trạng phòng</div>
                <div class="card-body chart-container  chart-body">
                    <canvas id="roomsChart" data-available="{{ $roomsData['available'] }}"
                        data-booked="{{ $roomsData['booked'] }}"
                        data-maintenance="{{ $roomsData['under_maintenance'] }}"
                        data-use="{{ $roomsData['in_use'] }}"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Đặt phòng theo ngày (7 ngày gần đây)</div>
                <div class="card-body chart-container chart-body">
                    <canvas id="reservationsChart" data-labels="{{json_encode($labels)}}"
                        data-data="{{ json_encode($data)}}"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom-scripts')
<script>
$(document).ready(function() {
    Chart.register({
        id: 'centerTextPlugin',
        beforeDraw: function(chart) {
            if (chart.config.type === 'doughnut') {
                var width = chart.chartArea.right - chart.chartArea.left,
                    height = chart.chartArea.bottom - chart.chartArea.top,
                    ctx = chart.ctx;

                ctx.restore();

                var fontSize = (Math.min(width, height) / 15).toFixed(2);
                ctx.font = fontSize + "px sans-serif";
                ctx.textBaseline = "middle";
                ctx.textAlign = 'center'; // Căn giữa văn bản theo chiều ngang

                var text = "Tổng: {{ $totalRooms }}",
                    textX = chart.chartArea.left + (width / 2), // Vị trí X chính giữa biểu đồ
                    textY = chart.chartArea.top + (height / 2); // Vị trí Y chính giữa biểu đồ

                ctx.fillText(text, textX, textY);
                ctx.save();
            }
        }
    });




    // Lấy dữ liệu từ thẻ HTML
    var roomsDataAvailable = $('#roomsChart').data('available');
    var roomsDataBooked = $('#roomsChart').data('booked');
    var roomsDataMaintenance = $('#roomsChart').data('maintenance');
    var roomsDataUse = $('#roomsChart').data('use');
    var labels = $('#reservationsChart').data('labels');
    var data = $('#reservationsChart').data('data');

    // Biểu đồ phòng
    var roomsCtx = document.getElementById('roomsChart').getContext('2d');
    var roomsChart = new Chart(roomsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Phòng trống', 'Phòng đã đặt', 'Phòng đang sử dụng', 'Phòng đang sửa'],
            datasets: [{
                data: [roomsDataAvailable, roomsDataBooked, roomsDataUse, roomsDataMaintenance],
                backgroundColor: ['#28a745', '#dc3545', '#ffc107', '#6c757d'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // Biểu đồ đặt phòng theo ngày
    var reservationsCtx = document.getElementById('reservationsChart').getContext('2d');
    var reservationsChart = new Chart(reservationsCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Số đơn đặt phòng',
                data: data,
                borderColor: '#007bff',
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Co dãn theo kích thước container
        }
    });
});
</script>
@endsection