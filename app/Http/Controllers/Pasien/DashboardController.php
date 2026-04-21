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
    $user = Auth::user();
    
    // Karena data profil ada di tabel users, gunakan langsung $user->id
    $activeRegistration = DaftarPoli::where('id_pasien', $user->id)
        ->whereIn('status', ['pending', 'proses'])
        ->with(['jadwalPeriksa.dokter', 'jadwalPeriksa.poli'])
        ->first();

    $schedules = JadwalPeriksa::with(['poli', 'dokter'])->get();
    $canRegister = !$activeRegistration;

    return view('pasien.dashboard.index', compact('activeRegistration', 'schedules', 'canRegister'));
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
}
