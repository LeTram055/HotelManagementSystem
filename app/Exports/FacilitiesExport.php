<?php

namespace App\Exports;

use App\Models\Facilities;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FacilitiesExport implements FromCollection, WithHeadings, WithMapping
{
    
    public function collection()
    {
        return Facilities::all();
    }

    public function headings(): array
    {
        return [
            'Mã tiện nghi',
            'Tên tiện nghi',
            'Mô tả',
        ];
    }

    public function map($facility): array
    {
        return [
            $facility->facility_id,
            $facility->facility_name,
            $facility->facility_description,
        ];
    }
}