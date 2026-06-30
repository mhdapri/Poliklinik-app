<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Models\DaftarPoli;
use App\Models\Periksa;      
use App\Models\DetailPeriksa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PeriksaPasienController extends Controller
{

    public function index() {
        // Ambil antrian yang statusnya 'pending' atau 'proses' untuk dokter yang login
        $antrians = DaftarPoli::whereHas('jadwalPeriksa', function($q) {
            $q->where('id_dokter', Auth::id());
        })->where('status', '!=', 'selesai')->orderBy('no_antrian')->get();

        return view('dokter.periksa-pasien.index', compact('antrians'));
    }
    public function create($id)
    {
        $antrian = DaftarPoli::with('pasien')->findOrFail($id);
        $obats = \App\Models\Obat::all(); // Ambil semua obat untuk resep
        return view('dokter.periksa-pasien.create', compact('antrian', 'obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required',
            'catatan' => 'required',
            'id_obat' => 'nullable|array',
            'jumlah' => 'nullable|array',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // 1. Simpan ke tabel periksa (Biaya Jasa Dokter 150rb)
            $biayaPeriksa = 150000; 
            
            $periksa = Periksa::create([
                'id_daftar_poli' => $request->id_daftar_poli,
                'tgl_periksa' => now(),
                'catatan' => $request->catatan,
                'biaya_periksa' => $biayaPeriksa,
            ]);

            $totalHargaObat = 0;

            // 2. Olah Obat dan Potong Stok
            if ($request->has('id_obat')) {
                foreach ($request->id_obat as $obatId) {
                    $jumlah = (int) ($request->jumlah[$obatId] ?? 1);
                    $obat = Obat::lockForUpdate()->findOrFail($obatId);

                    if ($obat->stok <= 0) {
                        throw new \Exception("Obat {$obat->nama_obat} sudah habis.");
                    }

                    if ($obat->stok < $jumlah) {
                        throw new \Exception("Stok {$obat->nama_obat} hanya tersisa {$obat->stok}.");
                    }
                    $obat->stok -= $jumlah;
                    $obat->save();

                    DetailPeriksa::create([
                        'id_periksa' => $periksa->id,
                        'id_obat' => $obatId,
                        'jumlah' => $jumlah,
                    ]);

                    $totalHargaObat += $obat->harga * $jumlah;
                }
            }

            // 3. Update total biaya
            $periksa->update([
                'biaya_periksa' => $biayaPeriksa + $totalHargaObat
            ]);

            // 4. Update status antrian
            DaftarPoli::where('id', $request->id_daftar_poli)->update(['status' => 'selesai']);

            DB::commit();
            return redirect()->route('periksa-pasien.index')->with('success', 'Data pemeriksaan berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
}