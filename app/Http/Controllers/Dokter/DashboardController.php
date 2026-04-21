<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use App\Models\Periksa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dokter = Auth::user();
        
        $jadwals = JadwalPeriksa::where('id_dokter', $dokter->id)
            ->with(['poli', 'daftarPolis.pasien'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();
        
        return view('dokter.dashboard', compact('jadwals'));
    }
}
