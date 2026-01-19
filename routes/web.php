<?php

use App\Http\Controllers\AcaraController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TamuController;
use Illuminate\Support\Facades\Route;

// --- FRONTEND (Tamu Undangan) ---
Route::get('/', function () {
    return view(view: 'auth.login');
});

// Route buat undangan (Contoh: /u/uuid-kode-unik)
Route::get('/u/{kode_unik}', [TamuController::class, 'showUndangan'])->name('undangan.show');
Route::post('/rsvp/{tamu}', [TamuController::class, 'submitRsvp'])->name('rsvp.submit');

// --- BACKEND (Admin/Petugas) ---
Route::middleware(['auth'])->group(function () {

    // Dashboard bisa dibuka Admin & Petugas
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==========================================================
    // 1. ROUTE KHUSUS ADMINISTRATOR
    // ==========================================================
    Route::middleware(['role:Administrator'])->group(function () {
        Route::resource('tamu', TamuController::class)->except(['show']);
        Route::resource('acara', AcaraController::class);

        // Route buat generate QR (kalau mau dibikin tombol khusus)
        Route::get('/tamu/{tamu}/generate-qr', [TamuController::class, 'generateQr'])->name('tamu.generateQr');
    });

    // ==========================================================
    // 2. ROUTE KHUSUS PETUGAS (Atau Admin juga boleh scan)
    // ==========================================================
    Route::middleware(['role:Petugas,Administrator'])->group(function () {
        // SESUAIKAN: Method di controller kita tadi namanya index & checkin
        Route::get('/scan', [CheckinController::class, 'index'])->name('scan');
        Route::post('/checkin', [CheckinController::class, 'checkin'])->name('checkin.process');
    });

    // ==========================================================
    // 3. ROUTE BERSAMA (Laporan & Profile)
    // ==========================================================
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
