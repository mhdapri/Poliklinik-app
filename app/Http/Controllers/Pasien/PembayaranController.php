<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index()
    {
        $pasienId = Auth::user()->pasien->id;
        
        // Menampilkan daftar pemeriksaan yang butuh pembayaran
        $tagihan = DaftarPoli::where('id_pasien', $pasienId)
            ->whereHas('periksa')
            ->with(['periksa.detailPeriksas.obat', 'pembayaran', 'jadwalPeriksa.poli', 'jadwalPeriksa.dokter'])
            ->latest()
            ->paginate(10);

        return view('pasien.pembayaran.index', compact('tagihan'));
    }

    public function show($id_daftar_poli)
    {
        $daftarPoli = DaftarPoli::with(['periksa.detailPeriksas.obat', 'pasien'])->findOrFail($id_daftar_poli);
        
        // Proteksi: Pastikan pasien hanya bisa lihat tagihan miliknya sendiri
        if ($daftarPoli->id_pasien !== Auth::user()->pasien->id) {
            abort(403, 'Akses ditolak.');
        }

        $biayaJasaDokter = 150000;
        $totalHargaObat = $daftarPoli->periksa->detailPeriksas->sum(fn($d) => $d->obat->harga ?? 0);
        $biayaTotal = $biayaJasaDokter + $totalHargaObat;

        return view('pasien.pembayaran.upload', compact('daftarPoli', 'biayaTotal'));
    }

    public function create($id) // Menampilkan Form Upload
    {
        $user = Auth::user();
        $pembayaran = Pembayaran::findOrFail($id);
        
        return view('pasien.pembayaran.create', compact('pembayaran'));
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Poin 6.b [cite: 101]
        ]);

        $pembayaran = Pembayaran::findOrFail($id);
        
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/bukti_bayar', $filename);
            
            $pembayaran->update([
                'bukti_pembayaran' => $filename,
                'status' => 'pending_verification' // Alur Poin 6.b [cite: 102]
            ]);
        }
        
        return redirect()->route('pasien.pembayaran.index')->with('success', 'Bukti berhasil diunggah!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        Pembayaran::updateOrCreate(
            ['id_daftar_poli' => $request->id_daftar_poli],
            [
                'biaya_total' => $request->biaya_total,
                'bukti_pembayaran' => $path,
                'status' => 'pending_verification'
            ]
        );

        return redirect()->route('pasien.pembayaran.index')->with('success', 'Bukti berhasil diunggah!');
    }

    public function verifikasi(Request $request, $id)
    {
        $pembayaran = \App\Models\Pembayaran::findOrFail($id);
        
        // Admin memilih status: verified atau rejected
        $pembayaran->update([
            'status' => $request->status, // 'verified' atau 'rejected'
            'catatan_admin' => $request->catatan // jika ada alasan penolakan
        ]);

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
