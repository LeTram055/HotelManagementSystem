<?php
// app/Exports/PaymentsExport.php

namespace App\Exports;

use App\Models\Payments;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Payments::with(['employee', 'reservation.customer'])->get();
    }

    public function headings(): array
    {
        return [
            'Mã thanh toán',
            'Số tiền',
            'Nhân viên',
            'Khách hàng',
            'Ngày thanh toán',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->payment_id,
            $payment->payment_amount,
            $payment->employee->employee_name,
            $payment->reservation->customer->customer_name,
            $payment->payment_date,
        ];
    }
}