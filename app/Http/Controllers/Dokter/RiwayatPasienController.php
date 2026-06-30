<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RiwayatPasienController extends Controller
{
    private function getDokterId()
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'Anda belum login.');
        }

        return $user->id;
    }

    public function index()
    {
        $dokterId = $this->getDokterId();

        $riwayat = \App\Models\DaftarPoli::with(['pasien', 'periksa'])
            ->whereHas('jadwalPeriksa', function ($q) use ($dokterId) {
                $q->where('id_dokter', $dokterId);
            })
            ->whereHas('periksa')
            ->where('status', 'selesai')
            ->paginate(15);

        return view('dokter.riwayat.index', compact('riwayat'));
    }

    public function show($id)
    {
        $dokterId = $this->getDokterId();

        $riwayat = \App\Models\DaftarPoli::with(['pasien', 'periksa'])
            ->whereHas('jadwalPeriksa', function ($q) use ($dokterId) {
                $q->where('id_dokter', $dokterId);
            })
            ->where('id', $id)
            ->first();

        if (! $riwayat) {
            abort(404);
        }

        return view('dokter.riwayat.show', compact('riwayat'));
    }

    public function export()
    {
        $dokterId = $this->getDokterId();

        $riwayat = \App\Models\DaftarPoli::with(['pasien', 'periksa'])
            ->whereHas('jadwalPeriksa', function ($q) use ($dokterId) {
                $q->where('id_dokter', $dokterId);
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