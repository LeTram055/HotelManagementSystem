<?php

namespace App\Exports;

use App\Models\Types;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TypesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Types::all();
    }

    public function headings(): array
    {
        return [
            'Mã loại phòng',
            'Tên loại phòng',
            'Giá (VNĐ)',
            'Sức chứa',
            'Diện tích',
            'Tiện nghi',
        ];
    }

    public function map($type): array
    {
        return [
            $type->type_id,
            $type->type_name,
            $type->type_price,
            $type->type_capacity,
            $type->type_area,
            implode(', ', $type->facilities->map(function($facility) {
            return $facility->facility_name . " - " . $facility->facility_description;
            })->toArray())
        ];
    }
}