<?php

namespace App\Exports;

use App\Models\SchoolClass;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SchoolClassExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return SchoolClass::orderBy('jurusan', 'asc')->get()->map(function ($schoolClass, $key) {
            return [
                'No' => $key + 1,
                'Nama Kelas' => $schoolClass->nama_kelas,
                'Jurusan' => $schoolClass->jurusan,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama Kelas', 'Jurusan'];
    }
}
