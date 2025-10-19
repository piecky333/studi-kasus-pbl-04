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
    PengaduanController as AdminPengaduanController,
    PrestasiController as AdminPrestasiController
};
use App\Http\Controllers\Pengurus\{
    DivisiController as PengurusDivisiController,
    DashboardController
};

// ===========================
// ROUTE PUBLIC
// ===========================
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

    // ---- CRUD BERITA ----
    Route::get('/berita', [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/create', [AdminBeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}/edit', [AdminBeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [AdminBeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [AdminBeritaController::class, 'destroy'])->name('berita.destroy');

    // ---- DIVISI ----
    Route::get('/divisi', [AdminDivisiController::class, 'index'])->name('divisi.index');
    Route::get('/divisi/{id}', [AdminDivisiController::class, 'show'])->name('divisi.show');

    // ---- PENGADUAN ----
    Route::get('/pengaduan', [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{id}', [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
    Route::put('/pengaduan/{id}/verifikasi', [AdminPengaduanController::class, 'verifikasi'])->name('pengaduan.verifikasi');
    Route::delete('/pengaduan/{id}', [AdminPengaduanController::class, 'destroy'])->name('pengaduan.destroy');

    // ---- PRESTASI (CRUD + AJAX Search Mahasiswa) ----
    Route::resource('prestasi', AdminPrestasiController::class);
    Route::get('/prestasi/cari-mahasiswa', [AdminPrestasiController::class, 'cariMahasiswa'])
    ->name('prestasi.cariMahasiswa');


});

// ===========================
// ROUTE UNTUK PENGURUS
// ===========================
Route::prefix('pengurus')->name('pengurus.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('divisi', PengurusDivisiController::class);
    Route::resource('jabatan', \App\Http\Controllers\Pengurus\JabatanController::class);
    Route::resource('pengurus', \App\Http\Controllers\Pengurus\PengurusController::class);
    Route::resource('keuangan', \App\Http\Controllers\Pengurus\KeuanganController::class);
});
  
// ===========================
// ROUTE UNTUK USER BIASA
// ===========================
Route::resource('berita', BeritaController::class);
Route::resource('pengaduan', PengaduanController::class);
