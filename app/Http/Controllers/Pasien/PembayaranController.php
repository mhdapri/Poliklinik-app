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
        $pasien = Auth::user();
        
       $pendingPayments = Pembayaran::whereHas('daftarPoli', function ($query) use ($pasien) {
            $query->where('id_pasien', $pasien->id);
        })
            ->with('daftarPoli.jadwalPeriksa.poli', 'daftarPoli.jadwalPeriksa.dokter')
            ->orderByDesc('created_at')
            ->paginate(10);
        
        return view('pasien.pembayaran.index', compact('pendingPayments'));
    }

    public function show($id)
    {
        $pasien = Auth::user();
        
        $pembayaran = Pembayaran::with('daftarPoli.jadwalPeriksa.poli', 'daftarPoli.jadwalPeriksa.dokter')
            ->whereHas('daftarPoli', function ($query) use ($pasien) {
                $query->where('id_pasien', $pasien->id);
            })
            ->findOrFail($id);
        
        return view('pasien.detail-pembayaran', compact('pembayaran'));
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
}
