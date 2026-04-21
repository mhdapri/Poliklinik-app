<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use App\Services\ExportService;
use Illuminate\Support\Facades\Auth;

class RiwayatPeriksaController extends Controller
{
    public function index()
    {
        $dokter = Auth::user();
        
        $riwayat = Periksa::whereHas('daftarPoli.jadwalPeriksa', function ($query) use ($dokter) {
            $query->where('id_dokter', $dokter->id);
        })
            ->with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.poli', 'detailPeriksas.obat'])
            ->orderByDesc('tgl_periksa')
            ->paginate(15);
        
        return view('dokter.riwayat-periksa.index', compact('riwayat'));
    }

    public function show($periksaId)
    {
        $dokter = Auth::user();
        
        $periksa = Periksa::whereHas('daftarPoli.jadwalPeriksa', function ($query) use ($dokter) {
            $query->where('id_dokter', $dokter->id);
        })
            ->with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.poli', 'detailPeriksas.obat'])
            ->findOrFail($periksaId);
        
        return view('dokter.riwayat-periksa.show', compact('periksa'));
    }

    public function export()
    {
        $dokter = Auth::user();
        $data = ExportService::exportRiwayatPeriksaData($dokter->id);
        
        return $this->streamCSV('Riwayat_Periksa_' . now()->format('Y-m-d_His') . '.csv', $data);
    }

    private function streamCSV($filename, $data)
    {
        $headers = [
            "Content-type" => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        return response()->stream(
            function () use ($data) {
                $handle = fopen('php://output', 'w');
                fwrite($handle, "\xEF\xBB\xBF");
                foreach ($data as $row) {
                    fputcsv($handle, $row, ';');
                }
                fclose($handle);
            },
            200,
            $headers
        );
    }
}
