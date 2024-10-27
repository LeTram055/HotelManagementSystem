<?php
namespace App\Exports;

use App\Models\Employees;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Employees::all();
    }

    public function headings(): array
    {
        return [
            'Mã nhân viên',
            'Tên nhân viên',
            'Số điện thoại',
            'Email',
            'Địa chỉ',
            'Tình trạng',
            'Mã tài khoản'
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->employee_id,
            $employee->employee_name,
            $employee->employee_phone,
            $employee->employee_email,
            $employee->employee_address,
            $employee->employee_status,
            $employee->account_id,
        ];
    }
}