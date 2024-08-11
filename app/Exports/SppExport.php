<?php

namespace App\Exports;

use App\Models\Spp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SppExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // return Spp::all();
        return Spp::select('id', 'tahun', 'nominal')->get();
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

    /**
     * Menentukan pemetaan kolom dalam file Excel.
     *
     * @param mixed $item
     * @return array
     */
    public function map($item): array
    {
        static $rowNumber = 1;

        return [
            $rowNumber++, // Menambah nomor urut
            $item->tahun,
            $item->nominal,
            // number_format($item->nominal, 0, ',', '.'), // Format nominal dengan titik setiap 3 digit
        ];
    }
}
