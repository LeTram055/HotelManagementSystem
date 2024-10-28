<?php

namespace App\Exports;

use App\Models\Reservations;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReservationsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Reservations::all();
    }

    public function headings(): array
    {   
        
        return [
            'Mã đặt phòng',
            'Khách hàng',
            'Loại phòng',
            'Phòng',
            'Ngày đặt',
            'Ngày nhận phòng',
            'Ngày trả phòng',
            'Trạng thái',
        ];
    }

    public function map($reservation): array
    {
        return [
            $reservation->reservation_id,
            $reservation->customer->customer_name,
            $reservation->rooms->pluck('type.type_name')->unique()->implode(', '),
            $reservation->rooms->pluck('room_name')->implode(', '),
            $reservation->checkin_date,
            $reservation->checkout_date,
            $reservation->status,
        ];
    }
}