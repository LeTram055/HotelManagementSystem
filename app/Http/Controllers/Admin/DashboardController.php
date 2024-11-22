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