<?php

namespace App\Exports;

use App\Models\Customers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    // Lấy dữ liệu từ cơ sở dữ liệu
    public function collection()
    {
        return Customers::all();
    }

    // Thêm tiêu đề cho các cột
    public function headings(): array
    {
        return [
            'Mã khách hàng',
            'Tên khách hàng',
            'Căn cước công dân',
            'Email',
            'Địa chỉ',
        ];
    }

    // Định dạng dữ liệu xuất ra
    public function map($customer): array
    {
        return [
            $customer->customer_id,
            $customer->customer_name,
            $customer->customer_cccd." ", // Thêm dấu ' để định dạng CCCD dưới dạng chuỗi
            $customer->customer_email,
            $customer->customer_address,
        ];
    }
}