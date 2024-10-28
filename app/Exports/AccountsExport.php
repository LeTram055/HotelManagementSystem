<?php

namespace App\Exports;

use App\Models\Accounts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Accounts::all();
    }

    public function headings(): array
    {
        return [
            'Mã tài khoản',
            'Tên tài khoản',
            'Vai trò',
            'Tình trạng hoạt động',
        ];
    }

    public function map($account): array
    {
        return [
            $account->account_id,
            $account->account_username,
            $account->account_role,
            $account->account_active,
        ];
    }
}