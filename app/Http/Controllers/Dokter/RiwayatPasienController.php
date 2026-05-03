<?php

namespace App\Http\Controllers\Dokter; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiwayatPasienController extends Controller
{
    public function index()
    {
        // 1. Ambil ID dokter yang sedang login
        $dokter = auth()->user()->dokter;

        // 2. Ambil data (Pastikan nama variabelnya '$riwayat' pakai R kecil)
        $riwayat = \App\Models\DaftarPoli::with(['pasien', 'periksa'])
            ->whereHas('jadwalPeriksa', function($q) use ($dokter) {
                $q->where('id_dokter', $dokter->id);
            })
            ->whereHas('periksa') // Hanya ambil data yang memiliki periksa
            ->where('status', 'selesai') // Sesuaikan dengan status di database kamu
            ->paginate(15);

        // 3. KIRIM KE VIEW (Ini bagian yang paling penting!)
        return view('dokter.riwayat.index', compact('riwayat')); 
    }

    public function show($id)
    {
        $dokter = auth()->user()->dokter;
        
        $riwayat = \App\Models\DaftarPoli::with(['pasien', 'periksa'])
            ->whereHas('jadwalPeriksa', function($q) use ($dokter) {
                $q->where('id_dokter', $dokter->id);
            })
            ->where('id', $id)
            ->first();

        if (!$riwayat) {
            abort(404);
        }

        return view('dokter.riwayat.show', compact('riwayat'));
    }

    public function export()
    {
        $dokter = auth()->user()->dokter;

        $riwayat = \App\Models\DaftarPoli::with(['pasien', 'periksa'])
            ->whereHas('jadwalPeriksa', function($q) use ($dokter) {
                $q->where('id_dokter', $dokter->id);
            })
            ->whereHas('periksa')
            ->where('status', 'selesai')
            ->get();

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\RiwayatExport($riwayat),
            'riwayat-pasien.xlsx'
        );
    }
}