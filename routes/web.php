<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController; 
use App\Http\Controllers\Admin\ObatController; 
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaran;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;

use App\Http\Controllers\Pasien\DashboardController as PasienDashboard;
use App\Http\Controllers\Pasien\RiwayatPendaftaranController;
use App\Http\Controllers\Pasien\PembayaranController as PasienPembayaran;
use App\Http\Controllers\Pasien\PoliController as PasienPoliController;


use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PeriksaPasienController;
use App\Http\Controllers\Dokter\DashboardController as DokterDashboard;
use App\Http\Controllers\Dokter\RiwayatPasienController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================= ADMIN ROLE =================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
    // Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');

    
    Route::get('/dokter/export', [DokterController::class, 'export'])->name('dokter.export');
    Route::get('/pasien/export', [PasienController::class, 'export'])->name('pasien.export');
    Route::get('/obat/export', [ObatController::class, 'export'])->name('obat.export');

    Route::resource('polis', PoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);
    Route::post('/obat/{id}/stock-adjust', [ObatController::class, 'adjustStock'])->name('obat.stock-adjust');
    
    
    Route::resource('pembayaran', AdminPembayaran::class)->only(['index', 'show'])->names('admin.pembayaran');
    Route::post('/pembayaran/{id}/verify', [AdminPembayaran::class, 'verify'])->name('admin.pembayaran.verify');
});

// ================= DOKTER ROLE =================
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', [DokterDashboard::class, 'index'])->name('dokter.dashboard');

    Route::resource('jadwal-periksa', JadwalPeriksaController::class);
    Route::post('/panggil/{id}', [JadwalPeriksaController::class, 'panggilAntrian'])
    ->name('dokter.panggil');
    
    // Route untuk menampilkan daftar antrian pasien
    Route::get('/periksa-pasien', [PeriksaPasienController::class, 'index'])->name('periksa-pasien.index');
   
    Route::get('/periksa-pasien/create/{id}', [PeriksaPasienController::class, 'create'])->name('periksa-pasien.create');

    // 2. Untuk memproses simpan data pemeriksaan (dan potong stok obat)
    Route::post('/periksa-pasien/store', [PeriksaPasienController::class, 'store'])->name('periksa-pasien.store');
    
    Route::get('/riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('riwayat-pasien.index');
    Route::get('/riwayat-pasien/export', [RiwayatPasienController::class, 'export'])->name('riwayat-pasien.export');
    Route::get('/riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])->name('riwayat-pasien.show');
    
});




Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', function () {
        return view('pasien.dashboard');
    })->name('pasien.dashboard');
    Route::get('/daftar', [PasienPoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/daftar', [PasienPoliController::class, 'submit'])->name('pasien.daftar.submit');
    Route::get('/daftar', [PasienPoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/daftar', [PasienPoliController::class, 'submit'])->name('pasien.daftar.submit');
    Route::get('/pembayaran', [PasienPembayaran::class, 'index'])->name('pasien.pembayaran.index');
    
    Route::get('/pembayaran/{id}', [PasienPembayaran::class, 'show'])->name('pasien.pembayaran.show');
    Route::post('/pembayaran/store', [PasienPembayaran::class, 'store'])->name('pasien.pembayaran.store');
    Route::get('/antrian/current/{id}', [PasienDashboard::class, 'getCurrentQueue']);
});

Route::get('/api/get-current-serving', function() {
    // Ambil nomor antrean yang statusnya 'proses' untuk setiap jadwal
    return \App\Models\DaftarPoli::where('status', 'proses')
        ->pluck('no_antrian', 'id_jadwal');
});
// Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    
//     // 1. Dashboard & Antrian (Folder: pasien/dashboard)
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('pasien.dashboard');
//     Route::post('/dashboard', [DashboardController::class, 'store'])->name('pasien.dashboard.store');
//     Route::get('/queue/{jadwal}', [DashboardController::class, 'getQueueUpdate'])->name('pasien.queue');

//     // 2. Riwayat Pendaftaran (Folder: pasien/riwayat) - Poin 3
//     Route::get('/riwayat', [RiwayatPendaftaranController::class, 'index'])->name('pasien.riwayat.index');
//     Route::get('/riwayat/{id}', [RiwayatPendaftaranController::class, 'show'])->name('pasien.riwayat.show');

//     // 3. Pembayaran (Folder: pasien/pembayaran) - Poin 6
//     Route::get('/pembayaran', [PasienPembayaran::class, 'index'])->name('pasien.pembayaran.index');
//     Route::get('/pembayaran/{id}/upload', [PasienPembayaran::class, 'create'])->name('pasien.pembayaran.create'); // Form Upload
//     Route::post('/pembayaran/{id}/upload', [PasienPembayaran::class, 'upload'])->name('pasien.pembayaran.store'); // Proses Simpan
// });