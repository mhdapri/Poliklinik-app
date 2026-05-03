<?php
namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\DaftarPoli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemeriksaPasienController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required',
            'catatan' => 'required',
            'id_obat' => 'nullable|array', // Dokter bisa memilih beberapa obat
        ]);

        // POIN 2.b.v: Proses dilakukan secara aman dan konsisten dengan Transaction
        DB::beginTransaction();

        try {
            // 1. Simpan ke tabel periksa
            // Biaya periksa standar (misal 150rb)
            $biayaPeriksa = 150000; 
            
            $periksa = Periksa::create([
                'id_daftar_poli' => $request->id_daftar_poli,
                'tgl_periksa' => now(),
                'catatan' => $request->catatan,
                'biaya_periksa' => $biayaPeriksa,
            ]);

            // 2. Jika ada obat yang dipilih
            if ($request->has('id_obat')) {
                $totalHargaObat = 0;

                foreach ($request->id_obat as $obatId) {
                    // Gunakan lockForUpdate agar stok tidak berubah saat proses berlangsung
                    $obat = Obat::lockForUpdate()->findOrFail($obatId);

                    // POIN 2.b.iv: Batalkan jika stok habis
                    if ($obat->stok <= 0) {
                        throw new \Exception("Stok obat '{$obat->nama_obat}' habis. Proses dibatalkan.");
                    }

                    // POIN 2.b.iii: Kurangi stok otomatis
                    $obat->stok -= 1; 
                    $obat->save();

                    // Simpan detail periksa (relasi periksa ke obat)
                    DetailPeriksa::create([
                        'id_periksa' => $periksa->id,
                        'id_obat' => $obatId,
                    ]);

                    $totalHargaObat += $obat->harga;
                }

                // Update total biaya periksa (biaya jasa + total harga obat)
                $periksa->update([
                    'biaya_periksa' => $biayaPeriksa + $totalHargaObat
                ]);
            }

            // Update status di DaftarPoli menjadi selesai
            DaftarPoli::where('id', $request->id_daftar_poli)->update(['status' => 'selesai']);

            DB::commit();
            return redirect()->back()->with('success', 'Hasil periksa berhasil disimpan!');

        } catch (\Exception $e) {
            // POIN 2.b.iv: Batalkan seluruh proses jika terjadi error/stok habis
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}