<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    BeritaController,
    JabatanController,
    PengaduanController
};
use App\Http\Controllers\Admin\{
    BeritaController as AdminBeritaController,
    DivisiController as AdminDivisiController,
    PengaduanController as AdminPengaduanController
};
use App\Http\Controllers\pengurus\{
    DivisiController as PengurusDivisiController,
    DashboardController
};

Route::get('/', fn() => view('public.home'));
Route::view('/divisi', 'public.divisi');
Route::view('/profile', 'public.profile');
Route::view('/berita', 'public.berita.index');
Route::view('/prestasiMahasiswa', 'public.prestasi');
Route::view('/laporan', 'public.laporan');
Route::view('/dashboard', 'pages.dashboard');

// ===========================
// ROUTE UNTUK ADMIN
// ===========================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('pages.admin.dashboard'))->name('dashboard');

    Route::get('/berita', [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/create', [AdminBeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}/edit', [AdminBeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [AdminBeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [AdminBeritaController::class, 'destroy'])->name('berita.destroy');

    Route::get('divisi', [AdminDivisiController::class, 'index'])->name('divisi.index');
    Route::get('divisi/{id}', [AdminDivisiController::class, 'show'])->name('divisi.show');

    Route::get('/pengaduan', [AdminPengaduanController::class, 'index']);
    Route::get('/pengaduan/{id}', [AdminPengaduanController::class, 'show']);
    Route::put('/pengaduan/{id}/verifikasi', [AdminPengaduanController::class, 'verifikasi']);
    Route::delete('/pengaduan/{id}', [AdminPengaduanController::class, 'destroy']);
});

// ===========================
// ROUTE UNTUK PENGURUS
// ===========================
Route::prefix('pengurus')->name('pengurus.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('divisi', PengurusDivisiController::class);
    Route::resource('jabatan', \App\Http\Controllers\pengurus\JabatanController::class);
    Route::resource('pengurus', \App\Http\Controllers\pengurus\PengurusController::class);
    Route::resource('keuangan', \App\Http\Controllers\pengurus\KeuanganController::class);

});

// ===========================
// ROUTE UNTUK USER BIASA
// ===========================
Route::resource('berita', BeritaController::class);
Route::resource('pengaduan', PengaduanController::class);
