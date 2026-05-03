<?php

namespace App\Exports;

use App\Models\Dokter;
use Maatwebsite\Excel\Concerns\FromCollection;

class DokterExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Dokter::all();
    }
}
