<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with('daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.poli')
            ->orderByDesc('created_at')
            ->paginate(15);
        
        return view('admin.pembayaran.index', compact('pembayaran'));
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with('daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.poli', 'daftarPoli.jadwalPeriksa.dokter')
            ->findOrFail($id);
        
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function verify(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'catatan_admin' => 'nullable|string',
        ]);
        
        $pembayaran->update([
            'status' => $request->status === 'verified' ? 'verified' : 'rejected',
            'catatan_admin' => $request->catatan_admin,
        ]);
        
        // Update payment_status in daftar_poli
        if ($request->status === 'verified') {
            $pembayaran->daftarPoli->update(['payment_status' => 'verified']);
        }
        
        return back()->with('success', 'Pembayaran berhasil diverifikasi');
    }

    public function export()
    {
        // Untuk implementasi export Excel
        // Akan di-implement dengan menggunakan package seperti Maatwebsite\Excel
    }
}
