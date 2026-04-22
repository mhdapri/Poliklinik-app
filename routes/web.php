<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController; 
use App\Http\Controllers\Admin\ObatController; 
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaran;

use App\Http\Controllers\Pasien\DashboardController;
use App\Http\Controllers\Pasien\RiwayatPendaftaranController;
use App\Http\Controllers\Pasien\PembayaranController as PasienPembayaran;


use App\Http\Controllers\Dokter\JadwalPeriksaController;

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
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');

    
    Route::get('/dokter/export', [DokterController::class, 'export'])->name('dokter.export');
    Route::get('/pasien/export', [PasienController::class, 'export'])->name('pasien.export');
    Route::get('/obat/export', [ObatController::class, 'export'])->name('obat.export');

    Route::resource('polis', PoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);
    
    
    Route::resource('pembayaran', AdminPembayaran::class)->only(['index', 'show']);
    Route::post('/pembayaran/{pembayaran}/verify', [AdminPembayaran::class, 'verify'])->name('pembayaran.verify');
});

// ================= DOKTER ROLE =================
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');

    Route::resource('jadwal-periksa', JadwalPeriksaController::class);
    
});

use App\Http\Controllers\Pasien\PoliController as PasienPoliController;

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', function () {
        return view('pasien.dashboard');
    })->name('pasien.dashboard');

    Route::get('/daftar', [PasienPoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/daftar', [PasienPoliController::class, 'submit'])->name('pasien.daftar.submit');
    Route::get('/pembayaran', [PasienPembayaran::class, 'index'])->name('pasien.pembayaran.index');
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