<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use App\Models\DaftarPoli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalPeriksaController extends Controller
{
    public function index(){
        $dokter = Auth::user();
        $jadwalPeriksas = JadwalPeriksa::where('id_dokter', $dokter->id)->orderBy('hari')->get();
        return view('dokter.jadwal-periksa.index', compact('jadwalPeriksas'));
    }

    public function create(){
        return view('dokter.jadwal-periksa.create');
    }

    public function store(Request $request){
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required'
        ]);

        JadwalPeriksa::create([
            'id_dokter' => Auth::id(),
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai
        ]);

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Data Berhasil di Simpan')
            ->with('type', 'success');
    }

    public function edit($id){
        $jadwalPeriksa = JadwalPeriksa::findOrFail($id);
        return view('dokter.jadwal-periksa.edit', compact('jadwalPeriksa'));
    }

    public function update(Request $request, string $id){
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required'
        ]);

        $jadwalPeriksa = JadwalPeriksa::findOrFail($id);
        $jadwalPeriksa->update([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai
        ]);

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Berhasil Melakukan Update Data')
            ->with('type', 'success');
    }

    public function destroy(string $id){
        $jadwalPeriksa = JadwalPeriksa::findOrFail($id);
        $jadwalPeriksa->delete();

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Berhasil Melakukan Hapus Data')
            ->with('type', 'success');
    }

    public function panggilAntrian($id)
    {
        $jadwal = JadwalPeriksa::findOrFail($id);

        // 1. Selesaikan antrian sebelumnya
        DaftarPoli::where('id_jadwal', $id)
            ->where('status', 'proses')
            ->update(['status' => 'selesai']);

        // 2. Ambil antrian berikutnya
        $antrian = DaftarPoli::where('id_jadwal', $id)
            ->where('status', 'pending')
            ->orderBy('no_antrian')
            ->first();

        if (!$antrian) {
            return back()->with('error', 'Tidak ada antrian.');
        }

        // 3. Update jadi diproses
        $antrian->update([
            'status' => 'proses'
        ]);

        // 4. Update nomor antrian sekarang
        $jadwal->update([
            'no_antrian_sekarang' => $antrian->no_antrian
        ]);

        return back()->with('success', 'Memanggil antrian nomor ' . $antrian->no_antrian);
    }
}