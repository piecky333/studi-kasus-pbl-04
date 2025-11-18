<?php

require __DIR__ . '/auth.php';

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;

// Import Controller SPK yang baru
use App\Http\Controllers\SpkManagementController;
use App\Http\Controllers\SpkCalculationController;

use App\Http\Controllers\public\{
    HomeController as PublicHomeController,
    DivisiController as PublicDivisiController,
    PrestasiController as PublicPrestasiController,
    BeritaController as publicBeritaController,
    KomentarController as PublicKomentarController 
};

use App\Http\Controllers\{
    BeritaController,
    JabatanController,
    PengaduanController
};

use App\Http\Controllers\user\{
    DashboardController as UserDashboardController,
    PengaduanController as UserPengaduanController,
    BeritaController as UserBeritaController
};

use App\Http\Controllers\Admin\{
    BeritaController as AdminBeritaController,
    DivisiController as AdminDivisiController,
    PengaduanController as AdminPengaduanController,
    PrestasiController as AdminPrestasiController,
    DashboardController as AdminDashboardController,
    SanksiController as AdminSanksiController
};


use App\Http\Controllers\pengurus\{
    DivisiController as PengurusDivisiController,
    PengurusDashboardController
};

// ===========================
// ROUTE UNTUK PUBLIC
// ===========================

Route::get('/', [PublicHomeController::class, 'index'])->name('home');

// --- Rute Berita ---
Route::get('/berita', [PublicBeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{berita}', [PublicBeritaController::class, 'show'])->name('berita.show'); // Menggunakan Route Model Binding

// --- Rute Divisi ---
Route::get('/divisi', [PublicDivisiController::class, 'index'])->name('divisi.index');
Route::get('/divisi/{id}', [PublicDivisiController::class, 'show'])->name('divisi.show');

// --- Rute Prestasi ---
Route::get('/prestasi', [PublicPrestasiController::class, 'index'])->name('prestasi.index');
Route::get('/prestasi/{id}', [PublicPrestasiController::class, 'show'])->name('prestasi.show');


// === TAMBAHKAN RUTE 'STORE' (BUAT) KOMENTAR DI SINI (PUBLIC) ===
Route::post('/berita/{id_berita}/komentar', [PublicKomentarController::class, 'store'])
    ->name('komentar.store');


// ===========================
// ROUTE UNTUK ADMIN
// ===========================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // ---- BERITA ----
    Route::get('/berita', [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/create', [AdminBeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}/edit', [AdminBeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [AdminBeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [AdminBeritaController::class, 'destroy'])->name('berita.destroy');

    // ---- MANAJEMEN AKUN ----
    Route::resource('pengurus', \App\Http\Controllers\Admin\PengurusController::class);

    // ---- DIVISI ----
    Route::get('/divisi', [AdminDivisiController::class, 'index'])->name('divisi.index');
    Route::get('/divisi/{id}', [AdminDivisiController::class, 'show'])->name('divisi.show');

    // ---- PENGADUAN ----
    Route::get('/pengaduan', [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{id}', [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
    Route::delete('/pengaduan/{id}', [AdminPengaduanController::class, 'destroy'])->name('pengaduan.destroy');
    Route::put('/pengaduan/{id}/verifikasi', [AdminPengaduanController::class, 'verifikasi'])->name('pengaduan.verifikasi');

    // ---- PRESTASI ----
    Route::resource('prestasi', AdminPrestasiController::class);
    Route::get('/prestasi/cari-mahasiswa', [AdminPrestasiController::class, 'cariMahasiswa'])
        ->name('prestasi.cariMahasiswa');

    // ---- SANKSI (CRUD) ----
    Route::get('/sanksi', [AdminSanksiController::class, 'index'])->name('sanksi.index');
    Route::get('/sanksi/create', [AdminSanksiController::class, 'create'])->name('sanksi.create');
    Route::post('/sanksi', [AdminSanksiController::class, 'store'])->name('sanksi.store');
    Route::get('/sanksi/{id}/edit', [AdminSanksiController::class, 'edit'])->name('sanksi.edit');
    Route::put('/sanksi/{id}', [AdminSanksiController::class, 'update'])->name('sanksi.update');
    Route::delete('/sanksi/{id}', [AdminSanksiController::class, 'destroy'])->name('sanksi.destroy');
    
    //ahp & saw
    Route::get('spk', [SpkManagementController::class, 'index'])->name('spk.index');
    Route::post('spk', [SpkManagementController::class, 'store'])->name('spk.store');
    Route::get('spk/create', [SpkManagementController::class, 'create'])->name('spk.create');

    
    // =========================================================================
    // RUTE BARU: SPK (SISTEM PENDUKUNG KEPUTUSAN)
    // URL: /admin/spk/{idKeputusan}/...
    // =========================================================================
    Route::prefix('spk/{idKeputusan}')->name('spk.')->group(function () {
        
        // 1. MANAJEMEN DATA (Data Master & Input)
        Route::prefix('manage')->name('manage.')->group(function () {
            // View Data Kriteria dan Sub Kriteria
            Route::get('kriteria', [SpkManagementController::class, 'showKriteria'])->name('kriteria');
            // View Data Alternatif
            Route::get('alternatif', [SpkManagementController::class, 'showAlternatif'])->name('alternatif');
            // View Data Penilaian (Matriks Keputusan - Xij)
            Route::get('penilaian', [SpkManagementController::class, 'showPenilaian'])->name('penilaian');
            // View Data Hasil Akhir (Output yang sudah disimpan)
            Route::get('hasil', [SpkManagementController::class, 'showHasilAkhir'])->name('hasil');
        });

        // 2. PERHITUNGAN (Proses & Eksekusi)
        Route::prefix('calculate')->name('calculate.')->group(function () {
            // View Data Perhitungan (Normalisasi dan Terbobot)
            Route::get('proses', [SpkCalculationController::class, 'showPerhitungan'])->name('proses');
            // Eksekusi Perhitungan & Simpan Hasil Akhir
            Route::get('run', [SpkCalculationController::class, 'runCalculation'])->name('run');
        });
    });
    // =========================================================================

});

// ===========================
// ROUTE UNTUK PENGURUS
// ===========================
Route::prefix('pengurus')->name('pengurus.')->middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/dashboard', [PengurusDashboardController::class, 'index'])->name('dashboard');
    Route::resource('divisi', PengurusDivisiController::class);
    Route::resource('jabatan', \App\Http\Controllers\Pengurus\JabatanController::class);
    Route::resource('pengurus', \App\Http\Controllers\Pengurus\PengurusController::class);
    Route::resource('keuangan', \App\Http\Controllers\Pengurus\KeuanganController::class);
});


// ===========================
// ROUTE UNTUK USER BIASA
// ===========================
Route::prefix('user')->name('user.')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])
        ->middleware(['auth'])->name('dashboard');
    Route::resource('berita', UserBeritaController::class);
    Route::resource('pengaduan', UserPengaduanController::class);
});

// ===========================
// autentikasi dengan Google
// ===========================
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');


// ===========================
// RUTE UMUM UNTUK USER LOGIN (PROFIL, KOMENTAR, DLL)
// ===========================
Route::middleware('auth')->group(function () {
    // --- Rute Profil (Sudah ada) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put('/komentar/{komentar}', [PublicKomentarController::class, 'update'])
        ->name('komentar.update');
    Route::delete('/komentar/{komentar}', [PublicKomentarController::class, 'destroy'])
        ->name('komentar.destroy');
});