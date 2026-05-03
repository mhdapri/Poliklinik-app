<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Periksa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        // 1. Ambil jumlah pasien yang statusnya belum 'selesai'
        $pasienMenunggu = DaftarPoli::whereHas('jadwalPeriksa', function($q) use ($dokterId) {
            $q->where('id_dokter', $dokterId);
        })->whereIn('status', ['pending', 'proses'])->count();

        // 2. Ambil jumlah riwayat pasien yang sudah diperiksa ('selesai')
        $totalRiwayat = DaftarPoli::whereHas('jadwalPeriksa', function($q) use ($dokterId) {
            $q->where('id_dokter', $dokterId);
        })->where('status', 'selesai')->count();

        // 3. Hitung total jadwal periksa yang pernah dibuat dokter ini
        $totalJadwal = JadwalPeriksa::where('id_dokter', $dokterId)->count();

        // 4. Ambil 5 data antrean terbaru untuk ditampilkan di tabel dashboard
        $antreanTerkini = DaftarPoli::with('pasien') // Eager load pasien agar tidak error "nama on null"
            ->whereHas('jadwalPeriksa', function($q) use ($dokterId) {
                $q->where('id_dokter', $dokterId);
            })
            ->where('status', '!=', 'selesai')
            ->orderBy('no_antrian', 'asc')
            ->limit(5)
            ->get();

        // MENGIRIMKAN SEMUA VARIABEL KE VIEW
        return view('dokter.dashboard', compact(
            'pasienMenunggu', 
            'totalRiwayat', 
            'totalJadwal', 
            'antreanTerkini'
        ));
        }
}
