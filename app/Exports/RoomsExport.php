<?php

namespace App\Exports;

use App\Models\Rooms;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RoomsExport implements FromCollection, WithHeadings, WithMapping
{
    
    public function collection()
    {
        return Rooms::all();
    }

    public function headings(): array
    {
        return [
            'Mã phòng',
            'Tên phòng',
            'Loại phòng',
            // 'Tình trạng phòng',
            'Ghi chú'
        ];
    }

    public function map($room): array
    {
        return [
            $room->room_id,
            $room->room_name,
            $room->type->type_name,
            // $room->status->status_name,
            $room->room_note
        ];
    }
}