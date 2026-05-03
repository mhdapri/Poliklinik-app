<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RiwayatExport implements FromCollection, WithHeadings
{
    protected $riwayat;

    public function __construct($riwayat)
    {
        $this->riwayat = $riwayat;
    }

    public function headings(): array
    {
        return [
            'Tanggal Periksa',
            'Nama Pasien',
            'Keluhan',
            'Catatan Dokter',
            'Biaya Periksa',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->riwayat->map(function($item) {
            return [
                'tgl_periksa' => \Carbon\Carbon::parse($item->periksa->tgl_periksa)->format('d M Y'),
                'nama_pasien' => $item->pasien->nama,
                'keluhan' => $item->keluhan,
                'catatan' => $item->periksa->catatan ?? '-',
                'biaya_periksa' => 'Rp ' . number_format($item->periksa->biaya_periksa, 0, ',', '.'),
            ];
        });
    }
}
