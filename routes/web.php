<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Import Controller yang akan digunakan (meskipun belum dibuat, kita definisikan dulu)
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\AcaraController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// FRONTEND (Tamu Undangan)
Route::get('/', function () {
    return view('welcome');
});

// Route untuk undangan perorangan (Contoh: /u/{kode_unik})
Route::get('/u/{kode_unik}', [TamuController::class, 'showUndangan'])->name('undangan.show');
Route::post('/rsvp/{tamu}', [TamuController::class, 'submitRsvp'])->name('rsvp.submit');

// BACKEND (Admin/Petugas)
Route::middleware(['auth'])->group(function () {
    
    // DASHBOARD (Diakses oleh Admin & Petugas)
    // Ubah route dashboard default Breeze
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['verified'])->name('dashboard');

    // ==========================================================
    // 1. ROUTE KHUSUS ADMINISTRATOR (CRUD & Generate QR)
    // ==========================================================
    Route::middleware(['role:Administrator'])->group(function () {
        // CRUD Tamu (Manajemen Data Tamu)
        Route::resource('tamu', TamuController::class)->except(['show']); 
        // CRUD Acara (Manajemen Data Acara)
        Route::resource('acara', AcaraController::class)->except(['show']); 
        
        // Route untuk Generate Link & QR Code
        Route::get('/tamu/{tamu}/generate-qr', [TamuController::class, 'generateQr'])->name('tamu.generateQr');
    });

    // ==========================================================
    // 2. ROUTE KHUSUS PETUGAS (Scan QR)
    // ==========================================================
    Route::middleware(['role:Petugas'])->group(function () {
        // [cite_start]// Halaman Scanner QR Code [cite: 40]
        Route::get('/scan', [CheckinController::class, 'showScanner'])->name('scan');
        // Endpoint untuk proses check-in
        Route::post('/checkin', [CheckinController::class, 'processCheckin'])->name('checkin.process');
    });

    // ==========================================================
    // 3. ROUTE BERSAMA (Admin & Petugas)
    // ==========================================================
    // [cite_start]// Laporan Kehadiran [cite: 40]
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

    // Route Profile bawaan Breeze (Admin/Petugas bisa ganti password/profil)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';