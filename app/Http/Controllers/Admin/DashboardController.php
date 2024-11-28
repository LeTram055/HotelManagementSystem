<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Rooms;
use App\Models\Customers;
use App\Models\Employees;
use App\Models\Reservations;


class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê dữ liệu cho dashboard
        $totalRooms = Rooms::count();
        
        $totalCustomers = Customers::count();
        $totalEmployees = Employees::count();
        $totalReservations = Reservations::count();
        $todayReservations = Reservations::whereDate('reservation_date', now())->count(); // Đặt phòng hôm nay

        $startDate = now()->toDateString();
        $reservations = Reservations::where(function ($query) use ($startDate) {
            
        // Kiểm tra xem start_date có nằm trong khoảng checkin và checkout của đơn đặt phòng không
        $query->where(function($q) use ($startDate) {
            $q->where('reservation_checkin', '<=', $startDate)
              ->where('reservation_checkout', '>=', $startDate);
        });
        })->get();

        // Duyệt qua từng phòng và xác định trạng thái
        $rooms = Rooms::all();
        $roomStatuses = [];
        foreach ($rooms as $room) {
            $roomStatusF = 'Trống'; // Mặc định

            $foundInReservation = false;
            foreach ($reservations as $reservation) {
                if ($reservation->rooms->contains('room_id', $room->room_id)) {
                    $foundInReservation = true;
                    
                    if ($reservation->reservation_status === 'Đã nhận phòng') {
                        $roomStatusF = 'Đang sử dụng';
                        break;
                    } elseif ($reservation->reservation_status === 'Đã xác nhận') {
                        $roomStatusF = 'Đã đặt';
                    }
                    
                }
            }

            if (!$foundInReservation) {
                 //$roomStatus = 'Tất cả';
            $roomStatusF = $room->status->status_name === 'Đang sửa' ? 'Đang sửa' : 'Trống';
        }

            $roomStatuses[$room->room_id] = $roomStatusF;
        }

        $availableRooms = 0;
        $bookedRooms = 0;
        $inUseRooms = 0;
        $underMaintenanceRooms = 0;

        foreach ($roomStatuses as $status) {
            if ($status == 'Trống') {
                $availableRooms++;
            } elseif ($status == 'Đã đặt') {
                $bookedRooms++;
            } elseif ($status == 'Đang sử dụng') {
                $inUseRooms++;
            } elseif ($status == 'Đang sửa') {
                $underMaintenanceRooms++;
            }
        }

        // Dữ liệu cho biểu đồ phòng
        $roomsData = [
            'available' => $availableRooms,
            'booked' => $bookedRooms,
            'in_use' => $inUseRooms,
            'under_maintenance' => $underMaintenanceRooms,
        ];

        // Dữ liệu cho biểu đồ đặt phòng theo ngày
        $reservationsByDay = Reservations::where('reservation_status', '!=', 'Đã hủy')
            ->selectRaw('DATE(reservation_date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->take(7) // Lấy thống kê 7 ngày gần đây
            ->get();

        $labels = $reservationsByDay->pluck('date')->toArray(); // Ngày
        $data = $reservationsByDay->pluck('count')->toArray(); // Số đơn đặt phòng

        return view('admin.dashboard')
        ->with('totalRooms', $totalRooms)
        ->with('availableRooms', $availableRooms)
        ->with('totalCustomers', $totalCustomers)
        ->with('totalEmployees', $totalEmployees)
        ->with('totalReservations', $totalReservations)
        ->with('todayReservations', $todayReservations)
        ->with('roomsData', $roomsData)
        ->with('labels', $labels)
        ->with('data', $data);
    }


    public function index_employee()
    {
        // Thống kê dữ liệu cho dashboard
        $totalRooms = Rooms::count();
        $availableRooms = Rooms::where('status_id', 1)->count(); // Phòng trống
        $bookedRooms = Rooms::where('status_id', 3)->count(); // Phòng đã đặt
        $inUseRooms = Rooms::where('status_id', 2)->count(); // Phòng đang sử dụng
        $underMaintenanceRooms = Rooms::where('status_id', 4)->count(); // Phòng đang bảo trì
        $totalCustomers = Customers::count();
        $totalEmployees = Employees::count();
        $totalReservations = Reservations::count();
        $todayReservations = Reservations::whereDate('reservation_date', now())->count(); // Đặt phòng hôm nay

        // Dữ liệu cho biểu đồ phòng
        $roomsData = [
            'available' => $availableRooms,
            'booked' => $bookedRooms,
            'in_use' => $inUseRooms,
            'under_maintenance' => $underMaintenanceRooms,
        ];

        // Dữ liệu cho biểu đồ đặt phòng theo ngày
        $reservationsByDay = Reservations::selectRaw('DATE(reservation_date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->take(7) // Lấy thống kê 7 ngày gần đây
            ->get();

        $labels = $reservationsByDay->pluck('date')->toArray(); // Ngày
        $data = $reservationsByDay->pluck('count')->toArray(); // Số đơn đặt phòng

        return view('admin.dashboard_employee')
        ->with('totalRooms', $totalRooms)
        ->with('availableRooms', $availableRooms)
        ->with('totalCustomers', $totalCustomers)
        ->with('totalEmployees', $totalEmployees)
        ->with('totalReservations', $totalReservations)
        ->with('todayReservations', $todayReservations)
        ->with('roomsData', $roomsData)
        ->with('labels', $labels)
        ->with('data', $data);
    }
}