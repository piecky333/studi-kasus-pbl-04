<?php

require __DIR__ . '/auth.php';

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    BeritaController,
    JabatanController,
    PengaduanController
};

use App\Http\Controllers\user\{
    DashboardController as UserDashboardController,
    PengaduanController as UserPengaduanController
};

use App\Http\Controllers\Admin\{
    BeritaController as AdminBeritaController,
    DivisiController as AdminDivisiController,
    PengaduanController as AdminPengaduanController,
    PrestasiController as AdminPrestasiController,
    DashboardController as AdminDashboardController,
};

use App\Http\Controllers\pengurus\{
    DivisiController as PengurusDivisiController,
    PengurusDashboardController
};

// ===========================
// ROUTE UNTUK PUBLIC
// ===========================
Route::get('/', fn() => view('public.home'))->name('home');
Route::view('/divisi', 'public.divisi');
Route::view('/profile', 'public.profile');
Route::view('/berita', 'public.berita.index');
Route::view('/prestasi', 'public.prestasi');
Route::view('/laporan', 'public.laporan');
Route::view('/dashboard', 'pages.dashboard');

// ===========================
// ROUTE UNTUK ADMIN
// ===========================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/berita', [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/create', [AdminBeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}/edit', [AdminBeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [AdminBeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [AdminBeritaController::class, 'destroy'])->name('berita.destroy');
    
    // ----  MANAJEMEN AKUN   ----
    Route::resource('pengurus', \App\Http\Controllers\Admin\PengurusController::class);

    // ---- DIVISI ----
    Route::get('/divisi', [AdminDivisiController::class, 'index'])->name('divisi.index');
    Route::get('/divisi/{id}', [AdminDivisiController::class, 'show'])->name('divisi.show');

    // ---- PENGADUAN ----
    Route::get('/pengaduan', [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{id}', [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
    Route::delete('/pengaduan/{id}', [AdminPengaduanController::class, 'destroy'])->name('pengaduan.destroy');
    Route::put('/pengaduan/{id}/verifikasi', [AdminPengaduanController::class, 'verifikasi'])->name('pengaduan.verifikasi');

    // ---- PRESTASI  ----
    Route::resource('prestasi', AdminPrestasiController::class);
    Route::get('/prestasi/cari-mahasiswa', [AdminPrestasiController::class, 'cariMahasiswa'])
        ->name('prestasi.cariMahasiswa');

});

// ===========================
// ROUTE UNTUK PENGURUS
// ===========================
Route::prefix('pengurus')->name('pengurus.')->group(function () {
    Route::get('/dashboard', [PengurusDashboardController::class, 'index'])->name('dashboard');
    Route::resource('divisi', PengurusDivisiController::class);
    Route::resource('jabatan', \App\Http\Controllers\Pengurus\JabatanController::class);
    Route::resource('pengurus', \App\Http\Controllers\Pengurus\PengurusController::class);
    Route::resource('keuangan', \App\Http\Controllers\Pengurus\KeuanganController::class);
});


// ===========================
// ROUTE UNTUK USER BIASA
// ===========================
Route::get('/dashboard', [UserDashboardController::class, 'index'])
    ->middleware(['auth'])->name('dashboard');
Route::resource('berita', BeritaController::class);
Route::resource('pengaduan', UserPengaduanController::class);


// ===========================
// autentikasi dengan Google
// ===========================
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');


// ===========================
// merubah profil user
// ===========================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
