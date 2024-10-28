<?php

namespace App\Exports;

use App\Models\TypeImages;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TypeImagesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return TypeImages::all();
    }

    public function headings(): array
    {
        return [
            'Mã hình ảnh',
            'Loại phòng',
            'Hình ảnh'
        ];
    }

    public function map($typeImage): array
    {
        return [
            $typeImage->image_id,
            $typeImage->type->type_name,
            $typeImage->image_url
        ];
    }
}