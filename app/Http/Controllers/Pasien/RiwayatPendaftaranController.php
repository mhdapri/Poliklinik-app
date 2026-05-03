<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use Illuminate\Support\Facades\Auth;

class RiwayatPendaftaranController extends Controller
{
    public function index()
    {
        $pasien = Auth::user();
        
        $registrations = DaftarPoli::where('id_pasien', $pasien->id)
            ->with(['jadwalPeriksa.dokter', 'jadwalPeriksa.poli', 'periksa.detailPeriksas.obat'])
            ->orderByDesc('created_at')
            ->paginate(10);
        
        return view('pasien.riwayat.index', compact('registrations'));
    }

    public function detail($id)
    {
        $pasien = Auth::user();
        
        $daftarPoli = DaftarPoli::where('id', $id)
            ->where('id_pasien', $pasien->id)
            ->with(['jadwalPeriksa.dokter', 'jadwalPeriksa.poli', 'periksa.detailPeriksas.obat'])
            ->firstOrFail();
        
        $periksa = $daftarPoli->periksa;
        
        return view('pasien.riwayat.show', compact('daftarPoli', 'periksa'));
    }
}
