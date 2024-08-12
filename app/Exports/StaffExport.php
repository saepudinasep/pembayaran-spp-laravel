<?php

namespace App\Exports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StaffExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Staff::with('user')->orderBy('nama', 'asc')->get()->map(function ($staff, $key) {
            return [
                'No' => $key + 1,
                'Nama' => $staff->nama,
                'Email' => $staff->user->email,
                'Username' => $staff->user->username,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama', 'Email', 'Username'];
    }
}
