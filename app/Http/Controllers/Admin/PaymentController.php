<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Payments;
use App\Models\Employees;
use App\Models\Reservations;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payments::with(['employee', 'reservation.customer'])->get();
        return view('admin.payment.index')
            ->with('payments', $payments);
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
        // Validate dữ liệu đầu vào
        // $request->validate([
        //     'employee_id' => 'required|exists:employees,employee_id',
        //     'reservation_id' => 'required|exists:reservations,reservation_id',
        //     'payment_date' => 'required|date',
        //     'payment_price' => 'required|numeric|min:0',
        //     
        // ]);
        

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

        // Cập nhật trạng thái đặt phòng thành "Đã trả"
        $reservation->reservation_status = 'Đã trả phòng';
        $reservation->save();

        // Cập nhật trạng thái các phòng thành "Trống"
        foreach ($rooms as $room) {
            $room->update(['status_id' => 1]); 
        }

        // Điều hướng sau khi lưu thành công
        Session::flash('alert-info', 'Thêm mới thành công !!!');
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
}