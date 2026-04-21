<?php

namespace App\Services;

use App\Models\User;
use App\Models\Obat;
use App\Models\JadwalPeriksa;
use App\Models\Periksa;

class ExportService
{
    /**
     * Export doctors data to array format
     */
    public static function exportDokterData()
    {
        $dokters = User::where('role', 'dokter')
            ->with('poli')
            ->get();
        
        $data = [];
        $data[] = ['No', 'Nama Dokter', 'No KTP', 'No HP', 'Alamat', 'Poli', 'Email'];
        
        $no = 1;
        foreach ($dokters as $dokter) {
            $data[] = [
                $no++,
                $dokter->nama,
                $dokter->no_ktp,
                $dokter->no_hp,
                $dokter->alamat,
                $dokter->poli?->nama_poli ?? '-',
                $dokter->email,
            ];
        }
        
        return $data;
    }
    
    /**
     * Export patients data to array format
     */
    public static function exportPasienData()
    {
        $pasiens = User::where('role', 'pasien')
            ->get();
        
        $data = [];
        $data[] = ['No', 'Nama Pasien', 'No RM', 'No KTP', 'No HP', 'Alamat', 'Email'];
        
        $no = 1;
        foreach ($pasiens as $pasien) {
            $data[] = [
                $no++,
                $pasien->nama,
                $pasien->no_rm ?? '-',
                $pasien->no_ktp,
                $pasien->no_hp,
                $pasien->alamat,
                $pasien->email,
            ];
        }
        
        return $data;
    }
    
    /**
     * Export drugs data to array format
     */
    public static function exportObatData()
    {
        $obats = Obat::all();
        
        $data = [];
        $data[] = ['No', 'Nama Obat', 'Kemasan', 'Harga', 'Stok', 'Total Nilai Stok'];
        
        $no = 1;
        foreach ($obats as $obat) {
            $totalValue = $obat->harga * $obat->stok;
            $data[] = [
                $no++,
                $obat->nama_obat,
                $obat->kemasan,
                'Rp ' . number_format($obat->harga, 0, ',', '.'),
                $obat->stok,
                'Rp ' . number_format($totalValue, 0, ',', '.'),
            ];
        }
        
        return $data;
    }
    
    /**
     * Export doctor's schedule to array format
     */
    public static function exportJadwalData($dokterId)
    {
        $jadwals = JadwalPeriksa::where('id_dokter', $dokterId)
            ->with(['poli', 'daftarPolis.pasien'])
            ->get();
        
        $data = [];
        $data[] = ['No', 'Poli', 'Hari', 'Jam Mulai', 'Jam Selesai', 'Antrian Saat Ini'];
        
        $no = 1;
        foreach ($jadwals as $jadwal) {
            $data[] = [
                $no++,
                $jadwal->poli?->nama_poli ?? '-',
                $jadwal->hari,
                $jadwal->jam_mulai,
                $jadwal->jam_selesai,
                $jadwal->no_antrian_sekarang,
            ];
        }
        
        return $data;
    }
    
    /**
     * Export doctor's examination history to array format
     */
    public static function exportRiwayatPeriksaData($dokterId)
    {
        $periksas = Periksa::whereHas('daftarPoli.jadwalPeriksa', function ($query) use ($dokterId) {
            $query->where('id_dokter', $dokterId);
        })
            ->with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.poli', 'detailPeriksas.obat'])
            ->get();
        
        $data = [];
        $data[] = ['No', 'Nama Pasien', 'Poli', 'Tanggal Periksa', 'Catatan', 'Biaya Periksa', 'Obat-obatan'];
        
        $no = 1;
        foreach ($periksas as $periksa) {
            $obatList = $periksa->detailPeriksas
                ->map(fn($detail) => $detail->obat->nama_obat . ' (x' . $detail->jumlah . ')')
                ->implode(', ');
            
            $data[] = [
                $no++,
                $periksa->daftarPoli->pasien->nama,
                $periksa->daftarPoli->jadwalPeriksa->poli?->nama_poli ?? '-',
                $periksa->tgl_periksa,
                $periksa->catatan ?? '-',
                'Rp ' . number_format($periksa->biaya_periksa, 0, ',', '.'),
                $obatList,
            ];
        }
        
        return $data;
    }
}
