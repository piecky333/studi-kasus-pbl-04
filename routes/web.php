<?php

require __DIR__ . '/auth.php';

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleAuthController;

// Import Controller SPK yang baru
use App\Http\Controllers\SpkManagementController;
use App\Http\Controllers\SpkCalculationController;
use App\Http\Controllers\KeputusanController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SubKriteriaController;

// ===========================
// CONTROLLERS (GROUPED)
// ... (Bagian import controllers lainnya tetap) ...
// ===========================

use App\Http\Controllers\public\{
    HomeController as PublicHomeController,
    DivisiController as PublicDivisiController,
    PrestasiController as PublicPrestasiController,
    BeritaController as PublicBeritaController,
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

// =========================================================
// ROUTE PUBLIC (TETAP)
// =========================================================
Route::get('/', [PublicHomeController::class, 'index'])->name('home');
Route::get('/berita', [PublicBeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{berita}', [PublicBeritaController::class, 'show'])->name('berita.show');
Route::get('/divisi', [PublicDivisiController::class, 'index'])->name('divisi.index');
Route::get('/divisi/{id}', [PublicDivisiController::class, 'show'])->name('divisi.show');
Route::get('/prestasi', [PublicPrestasiController::class, 'index'])->name('prestasi.index');
Route::get('/prestasi/{id}', [PublicPrestasiController::class, 'show'])->name('prestasi.show');
Route::post('/berita/{id_berita}/komentar', [PublicKomentarController::class, 'store'])->name('komentar.store');


// =========================================================
// ROUTE ADMIN
// =========================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // ---- BERITA, PENGURUS, DIVISI, PENGADUAN, PRESTASI, SANKSI (TETAP) ----
    Route::get('/berita', [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/create', [AdminBeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}/edit', [AdminBeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [AdminBeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [AdminBeritaController::class, 'destroy'])->name('berita.destroy');

    Route::resource('pengurus', \App\Http\Controllers\Admin\PengurusController::class);
    Route::get('/divisi', [AdminDivisiController::class, 'index'])->name('divisi.index');
    Route::get('/divisi/{id}', [AdminDivisiController::class, 'show'])->name('divisi.show');
    Route::get('/pengaduan', [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{id}', [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
    Route::delete('/pengaduan/{id}', [AdminPengaduanController::class, 'destroy'])->name('pengaduan.destroy');
    Route::put('/pengaduan/{id}/verifikasi', [AdminPengaduanController::class, 'verifikasi'])->name('pengaduan.verifikasi');
    Route::resource('prestasi', AdminPrestasiController::class);
    Route::get('/prestasi/cari-mahasiswa', [AdminPrestasiController::class, 'cariMahasiswa'])->name('prestasi.cariMahasiswa');
    Route::get('/sanksi', [AdminSanksiController::class, 'index'])->name('sanksi.index');
    Route::get('/sanksi/create', [AdminSanksiController::class, 'create'])->name('sanksi.create');
    Route::post('/sanksi', [AdminSanksiController::class, 'store'])->name('sanksi.store');
    Route::get('/sanksi/{id}/edit', [AdminSanksiController::class, 'edit'])->name('sanksi.edit');
    Route::put('/sanksi/{id}', [AdminSanksiController::class, 'update'])->name('sanksi.update');
    Route::delete('/sanksi/{id}', [AdminSanksiController::class, 'destroy'])->name('sanksi.destroy');


    // -------------------------------------------------------------------------
    // RUTE SPK LEVEL 1 (MANAJEMEN KEPUTUSAN UMUM)
    // -------------------------------------------------------------------------
    Route::get('spk', [KeputusanController::class, 'index'])->name('spk.index');
    Route::post('spk', [KeputusanController::class, 'store'])->name('spk.store');
    Route::get('spk/create', [KeputusanController::class, 'create'])->name('spk.create');
    // Tambahkan CRUD Keputusan Umum
    Route::get('spk/{idKeputusan}/edit', [KeputusanController::class, 'edit'])->name('spk.edit');
    Route::put('spk/{idKeputusan}', [KeputusanController::class, 'update'])->name('spk.update');
    Route::delete('spk/{idKeputusan}', [KeputusanController::class, 'destroy'])->name('spk.destroy');


    // =========================================================================
    // RUTE SPK LEVEL 2 (DETAIL: KRITERIA, ALTERNATIF, PENILAIAN, PERHITUNGAN)
    // =========================================================================
    Route::prefix('spk/{idKeputusan}')->name('spk.')->group(function () {

        Route::prefix('manage')->name('manage.')->group(function () {

            // --- 1. CRUD KRITERIA ---
            // READ List
            Route::get('kriteria', [KriteriaController::class, 'index'])->name('kriteria');
            // CREATE
            Route::get('kriteria/create', [KriteriaController::class, 'create'])->name('kriteria.create');
            Route::post('kriteria', [KriteriaController::class, 'store'])->name('kriteria.store');
            // UPDATE
            Route::get('kriteria/{idKriteria}/edit', [KriteriaController::class, 'edit'])->name('kriteria.edit');
            Route::put('kriteria/{idKriteria}', [KriteriaController::class, 'update'])->name('kriteria.update');
            // DELETE
            Route::delete('kriteria/{idKriteria}', [KriteriaController::class, 'destroy'])->name('kriteria.destroy');

            // --- 1.1. CRUD SUB KRITERIA ---
            Route::prefix('kriteria/{idKriteria}')->name('kriteria.')->group(function () {
                Route::get('subkriteria', [SubKriteriaController::class, 'index'])->name('subkriteria');
                Route::get('subkriteria/create', [SubKriteriaController::class, 'create'])->name('subkriteria.create');
                Route::post('subkriteria', [SubKriteriaController::class, 'store'])->name('subkriteria.store');
                Route::get('subkriteria/{idSubKriteria}/edit', [SubKriteriaController::class, 'edit'])->name('subkriteria.edit');
                Route::put('subkriteria/{idSubKriteria}', [SubKriteriaController::class, 'update'])->name('subkriteria.update');
                Route::delete('subkriteria/{idSubKriteria}', [SubKriteriaController::class, 'destroy'])->name('subkriteria.destroy');
            });

            // --- 2. CRUD ALTERNATIF ---
            // READ List
            Route::get('alternatif', [AlternatifController::class, 'index'])->name('alternatif');
            // CREATE
            Route::get('alternatif/create', [AlternatifController::class, 'create'])->name('alternatif.create');
            Route::post('alternatif', [AlternatifController::class, 'store'])->name('alternatif.store');
            // UPDATE
            Route::get('alternatif/{idAlternatif}/edit', [AlternatifController::class, 'edit'])->name('alternatif.edit');
            Route::put('alternatif/{idAlternatif}', [AlternatifController::class, 'update'])->name('alternatif.update');
            // DELETE
            Route::delete('alternatif/{idAlternatif}', [AlternatifController::class, 'destroy'])->name('alternatif.destroy');


            // --- 3. DATA PENILAIAN & HASIL (VIEW) ---
            Route::get('penilaian', [PenilaianController::class, 'index'])->name('penilaian');
            Route::get('penilaian/edit', [PenilaianController::class, 'editMatriks'])->name('penilaian.edit');
            Route::put('penilaian', [PenilaianController::class, 'updateMatriks'])->name('penilaian.update');
            Route::get('hasil', [SpkManagementController::class, 'showHasilAkhir'])->name('hasil');
        });

        // 2. PERHITUNGAN (Proses & Eksekusi)
        Route::prefix('calculate')->name('calculate.')->group(function () {
            Route::get('proses', [SpkCalculationController::class, 'showPerhitungan'])->name('proses');
            Route::get('run', [SpkCalculationController::class, 'runCalculation'])->name('run');
        });
    });
    // =========================================================================

});


// =========================================================
// ROUTE PENGURUS, USER BIASA, LOGIN GOOGLE, UMUM (TETAP)
// =========================================================
Route::prefix('pengurus')->name('pengurus.')->middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/dashboard', [PengurusDashboardController::class, 'index'])->name('dashboard');
    Route::resource('divisi', PengurusDivisiController::class)->parameters(['divisi' => 'id_divisi']);
    Route::resource('jabatan', \App\Http\Controllers\Pengurus\JabatanController::class);
    Route::resource('pengurus', \App\Http\Controllers\Pengurus\PengurusController::class);
    Route::resource('keuangan', \App\Http\Controllers\Pengurus\KeuanganController::class);
});

Route::prefix('user')->name('user.')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::resource('berita', UserBeritaController::class);
    Route::resource('pengaduan', UserPengaduanController::class);
});

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/komentar/{komentar}', [PublicKomentarController::class, 'update'])->name('komentar.update');
    Route::delete('/komentar/{komentar}', [PublicKomentarController::class, 'destroy'])->name('komentar.destroy');
});
