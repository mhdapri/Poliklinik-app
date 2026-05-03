<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Obat;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil total jumlah data
        $totalPoli = Poli::count();
        $totalDokter = User::where('role', 'dokter')->count();
        $totalPasien = User::where('role', 'pasien')->count();
        $totalObat = Obat::count();

        // Mengambil data poli untuk tabel di dashboard (beserta jumlah dokter di tiap poli)
        $polis = Poli::withCount('dokter')->get();

        return view('admin.dashboard', compact(
            'totalPoli', 
            'totalDokter', 
            'totalPasien', 
            'totalObat', 
            'polis'
        ));
    }
}