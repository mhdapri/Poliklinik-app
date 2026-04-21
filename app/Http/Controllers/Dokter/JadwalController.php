<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use App\Services\ExportService;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $dokter = Auth::user();
        
        $jadwals = JadwalPeriksa::where('id_dokter', $dokter->id)
            ->with(['poli', 'daftarPolis.pasien'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(15);
        
        return view('dokter.jadwal.index', compact('jadwals'));
    }

    public function updateQueueNumber($jadwalId, $queueNumber)
    {
        $dokter = Auth::user();
        
        $jadwal = JadwalPeriksa::where('id_dokter', $dokter->id)
            ->findOrFail($jadwalId);
        
        $jadwal->update(['no_antrian_sekarang' => $queueNumber]);
        
        // Broadcast event untuk WebSocket update
        // event(new QueueNumberUpdated($jadwal));
        
        return response()->json(['success' => true]);
    }

    public function export()
    {
        $dokter = Auth::user();
        $data = ExportService::exportJadwalData($dokter->id);
        
        return $this->streamCSV('Jadwal_Periksa_' . now()->format('Y-m-d_His') . '.csv', $data);
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
