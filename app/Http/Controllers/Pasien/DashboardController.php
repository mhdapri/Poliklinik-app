<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
{
    if (!Auth::user()->pasien) {
        return redirect()->route('home')->with('error', 'Anda belum terdaftar sebagai pasien.');
    }
    $pasienId = Auth::user()->pasien->id;

    // 1. Cari antrean yang masih 'pending' atau 'proses' (Hanya 1)
    $antrianAktif = DaftarPoli::with(['jadwalPeriksa.dokter', 'jadwalPeriksa.poli'])
        ->where('id_pasien', $pasienId)
        ->whereIn('status', ['pending', 'proses'])
        ->first();

    // 2. Ambil semua jadwal untuk tabel bawah
    $jadwalPolis = JadwalPeriksa::with(['dokter', 'poli'])->get();

    return view('pasien.dashboard', compact('antrianAktif', 'jadwalPolis'));
}

    public function getQueueUpdate($jadwalId)
    {
        $jadwal = JadwalPeriksa::findOrFail($jadwalId);
        
        return response()->json([
            'no_antrian_sekarang' => $jadwal->no_antrian_sekarang,
            'daftar_polis' => $jadwal->daftarPolis()
                ->with('pasien')
                ->where('status', '!=', 'dibatalkan')
                ->get(['id', 'no_antrian', 'status'])
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'required|string|min:5',
        ]);

        $user = Auth::user();

        // Validasi pendaftaran ganda [cite: 41]
        $hasActive = DaftarPoli::where('id_pasien', $user->id)
            ->whereIn('status', ['pending', 'proses'])
            ->exists();

        if ($hasActive) {
            return back()->with('error', 'Anda masih memiliki antrian aktif.');
        }

        // Generate No Antrian
        $lastQueue = DaftarPoli::where('id_jadwal', $request->id_jadwal)->count();
        
        DaftarPoli::create([
            'id_pasien' => $user->id,
            'id_jadwal' => $request->id_jadwal,
            'keluhan' => $request->keluhan,
            'no_antrian' => $lastQueue + 1,
            'status' => 'pending',
        ]);

        return redirect()->route('pasien.dashboard')->with('success', 'Pendaftaran berhasil!');
    }

    public function dashboard()
    {
        $user = Auth::user();

        $antrianAktif = DaftarPoli::with('jadwal.dokter.poli')
            ->where('id_pasien', $user->id)
            ->whereNotIn('status', ['selesai', 'dibatalkan']) // atau status = 'menunggu'
            ->latest()
            ->first();

        return view('pasien.dashboard', compact('antrianAktif'));
    }
    public function getCurrentQueue($id)
    {
        $jadwal = JadwalPeriksa::findOrFail($id);

        return response()->json([
            'nomor' => $jadwal->no_antrian_sekarang ?? 0
        ]);
    }
}
