<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Payments;
use App\Models\Employees;
use App\Models\Reservations;
use App\Models\RoomReservation;
use App\Http\Controllers\Admin\NotificationController;

use App\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'payment_id'); // Mặc định sắp xếp theo payment_id
        $sortDirection = $request->input('sort_direction', 'asc'); // Mặc định sắp xếp tăng dần

        $query = Payments::join('employees', 'payments.employee_id', '=', 'employees.employee_id')
            ->join('reservations', 'payments.reservation_id', '=', 'reservations.reservation_id')
            ->join('customers', 'reservations.customer_id', '=', 'customers.customer_id')
            ->select('payments.*', 'employees.employee_name', 'customers.customer_name');

        // Kiểm tra nếu có input tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('payments.payment_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('payments.payment_price', 'like', '%' . $searchTerm . '%')
                    ->orWhere('employees.employee_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('customers.customer_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('payments.payment_date', 'like', '%' . $searchTerm . '%')
                    ->orWhere('payments.payment_method', 'like', '%' . $searchTerm . '%');
            });
        }

        // Kiểm tra nếu cột sắp xếp hợp lệ
        if (in_array($sortField, ['employee_name', 'customer_name', 'payment_date', 'payment_method'])) {
            $query->orderByRaw("CONVERT($sortField USING utf8) COLLATE utf8_unicode_ci $sortDirection");
        } elseif (in_array($sortField, ['payment_id', 'payment_price'])) {
            // Sắp xếp giá theo kiểu số
            $query->orderByRaw("CAST($sortField AS DECIMAL) $sortDirection");
        } else {
            $query->orderByRaw("CONVERT(payments.payment_id USING utf8) COLLATE utf8_unicode_ci asc");
        }

        $payments = $query->get();
        return view('admin.payment.index')
            ->with('payments', $payments)
            ->with('sortField', $sortField)
            ->with('sortDirection', $sortDirection);
    }

    public function create()
    {
        $employees = Employees::where('employee_name', '!=', 'admin')->get();
        $reservations = Reservations::where('reservation_status', 'Đã nhận phòng')
            ->with(['rooms.type', 'payments']) // Tải các phòng liên quan
            ->get()
            ->filter(function ($reservation) {
                // Lọc chỉ lấy những reservation chưa có payment
                return $reservation->payments->isEmpty(); // Kiểm tra nếu payment là null
            });
        return view('admin.payment.create')
            ->with('employees', $employees)
            ->with('reservations', $reservations);
        }

    // Lưu Payment mới vào database
    public function save(Request $request)
    {
        $request->validate([
        'reservation_id' => 'required|exists:reservations,reservation_id',
        'employee_id' => 'required|exists:employees,employee_id',
        'payment_method' => 'required|string|max:50',
    ], [
        'employee_id.required' => 'Nhân viên không được để trống',
        'employee_id.exists' => 'Nhân viên không tồn tại',
        'payment_method.required' => 'Phương thức thanh toán không được để trống',
        'payment_method.string' => 'Phương thức thanh toán phải là chuỗi ký tự',
        'payment_method.max' => 'Phương thức thanh toán không được vượt quá 50 ký tự',
        'reservation_id.required' => 'Đặt phòng không được để trống',
        'reservation_id.exists' => 'Đặt phòng không tồn tại',
    ]);
        

        // Lấy thông tin đặt phòng và tính toán giá trị thanh toán
        $reservation = Reservations::find($request->reservation_id);
        $rooms = $reservation->rooms;

        // Tính toán số ngày lưu trú
        $checkin = strtotime($reservation->reservation_checkin);
        $checkout = strtotime($reservation->reservation_checkout);
        $days = ($checkout - $checkin) / (60 * 60 * 24);  // Tính số ngày từ ngày checkin và checkout

        // Tính tổng giá trị dựa trên giá phòng và số ngày lưu trú
        $totalPrice = 0;
        foreach ($rooms as $room) {
            $totalPrice += $room->type->type_price * $days;
        }

        // Tạo mới Payment
        $payment = new Payments();
        $payment->employee_id = $request->employee_id;
        $payment->reservation_id = $request->reservation_id;
        $payment->payment_date = now();
        $payment->payment_price = $totalPrice;
        $payment->payment_method = $request->payment_method;
        $payment->save();

        // Cập nhật trạng thái đặt phòng thành "Đã trả phòng"
        $reservation->reservation_status = 'Đã trả phòng';
        $reservation->save();

// Cập nhật trạng thái phòng
    foreach ($rooms as $room) {
        // Kiểm tra nếu phòng này đã được xác nhận trong các đơn đặt phòng khác
        $otherReservations = RoomReservation::where('room_id', $room->room_id)
            ->whereHas('reservation', function ($query) {
                $query->where('reservation_status', 'Đã xác nhận');
            })
            ->exists();

        // Nếu có đơn đặt phòng xác nhận và chưa nhận phòng thì phòng này sẽ được "Đã đặt"
        if ($otherReservations) {
            $room->update(['status_id' => 3]); // Phòng đã đặt
        } else {
            $room->update(['status_id' => 1]); // Phòng trống
        }
    }

    $notificationController = new NotificationController();
    $message = "Cảm ơn bạn đã lưu trú tại khách sạn Ánh Dương. Hẹn gặp lại bạn lần sau.";
    $notificationController->createNotification($reservation->customer_id, $message);


    // Điều hướng sau khi lưu thành công
    Session::flash('alert-info', 'Thêm mới thành công và cập nhật trạng thái phòng !!!');
    return redirect()->route('admin.payment.index');
}

    public function edit(Request $request)
    {
        $payment = Payments::find($request->payment_id);
        $employees = Employees::where('employee_name', '!=', 'admin')->get();
        
        return view('admin.payment.edit')
            ->with('payment', $payment)
            ->with('employees', $employees);
    }

    public function update(Request $request)
{
    $request->validate([
        'payment_id' => 'required|exists:payments,payment_id',
        'employee_id' => 'required|exists:employees,employee_id',
        'payment_method' => 'required|string|max:50',
    ], [
        'employee_id.required' => 'Nhân viên không được để trống',
        'employee_id.exists' => 'Nhân viên không tồn tại',
        'payment_method.required' => 'Phương thức thanh toán không được để trống',
        'payment_method.string' => 'Phương thức thanh toán phải là chuỗi ký tự',
        'payment_method.max' => 'Phương thức thanh toán không được vượt quá 50 ký tự',
    ]);

    $payment = Payments::find($request->payment_id);
    $payment->employee_id = $request->employee_id;
    $payment->payment_method = $request->payment_method;
    $payment->save();

    Session::flash('alert-info', 'Cập nhật thành công !!!');
    return redirect()->route('admin.payment.index');
}

    public function destroy(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|integer|exists:payments,payment_id',
        ]);
        
        $payment = Payments::find($request->payment_id);
        $payment->destroy($request->payment_id);
        Session::flash('alert-info', 'Xóa thành công ^^~!!!');
        return redirect()->route('admin.payment.index');
    }

    public function export()
    {
        return Excel::download(new PaymentsExport, 'payments.xlsx');
    }

    public function exportInvoice($payment_id)
    {
        $payment = Payments::with(['employee', 'reservation.customer'])->findOrFail($payment_id);

        // Tạo dữ liệu cho hóa đơn
        $invoiceData = [
            'payment' => $payment,
            'customer' => $payment->reservation->customer,
            'employee' => $payment->employee,
            'formatted_price' => number_format($payment->payment_price, 0, ',', '.'),
            
        ];

        // Tải view hóa đơn
        $pdf = PDF::loadView('admin.payment.invoice', $invoiceData);

        // Xuất file PDF
        return $pdf->download('invoice_' . $payment->payment_id . '.pdf');
    }

}