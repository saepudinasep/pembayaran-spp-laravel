<?php

namespace App\Exports;

use App\Models\Spp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SppExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Spp::orderBy('tahun', 'desc')->get()->map(function ($spp, $key) {
            return [
                'No' => $key + 1,
                'Tahun' => $spp->tahun,
                'Nominal' => $spp->nominal,
            ];
        });
    }

    /**
     * Menentukan header untuk file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Tahun',
            'Nominal',
        ];
    }
}
