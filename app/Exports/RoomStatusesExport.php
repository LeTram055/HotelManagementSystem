<?php

namespace App\Exports;

use App\Models\RoomStatuses;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RoomStatusesExport implements FromCollection
{
    public function collection()
    {
        return RoomStatuses::all();
    }

    public function headings(): array
    {
        return [
            'Mã trạng thái',
            'Tên trạng thái',
        ];
    }

    // Định dạng dữ liệu xuất ra
    public function map($status): array
    {
        return [
            $status->status_id,
            $status->status_name,
        ];
    }
}