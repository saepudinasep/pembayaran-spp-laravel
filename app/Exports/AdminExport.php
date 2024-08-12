<?php

namespace App\Exports;

use App\Models\Admin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Admin::with('user')->orderBy('nama', 'desc')->get()->map(function ($admin, $key) {
            return [
                'No' => $key + 1,
                'Nama' => $admin->nama,
                'Email' => $admin->user->email,
                'Username' => $admin->user->username,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama', 'Email', 'Username'];
    }
}
