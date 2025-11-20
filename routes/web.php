<?php

require __DIR__ . '/auth.php';

use Illuminate\Support\Facades\Route;

// =========================================================
// PUBLIC CONTROLLERS
// =========================================================
use App\Http\Controllers\public\{
    HomeController as PublicHomeController,
    DivisiController as PublicDivisiController,
    PrestasiController as PublicPrestasiController,
    BeritaController as PublicBeritaController,
    KomentarController as PublicKomentarController
};

// =========================================================
// ADMIN CONTROLLERS
// =========================================================
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    BeritaController as AdminBeritaController,
    DivisiController as AdminDivisiController,
    PengaduanController as AdminPengaduanController,
    PrestasiController as AdminPrestasiController,
    SanksiController as AdminSanksiController,
    PengurusController as AdminPengurusController
};

// =========================================================
// USER CONTROLLERS
// =========================================================
use App\Http\Controllers\user\{
    DashboardController as UserDashboardController,
    PengaduanController as UserPengaduanController,
    BeritaController as UserBeritaController
};

// =========================================================
// PENGURUS CONTROLLERS
// =========================================================
use App\Http\Controllers\pengurus\{
    DivisiController as PengurusDivisiController,
    PengurusDashboardController
};

// =========================================================
// AUTENTIKASI & PROFIL
// =========================================================
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleAuthController;

// =========================================================
// SPK CONTROLLERS
// =========================================================
use App\Http\Controllers\SpkManagementController;
use App\Http\Controllers\SpkCalculationController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\KeputusanController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\PerbandinganKriteriaController; // Import Controller AHP

// =========================================================
// ROUTE — PUBLIC
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
// ROUTE — ADMIN
// =========================================================

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD berita
    Route::resource('berita', AdminBeritaController::class)->except(['show']);

    // CRUD Pengurus
    Route::resource('pengurus', AdminPengurusController::class);

    // CRUD Divisi
    Route::resource('divisi', AdminDivisiController::class)->only(['index', 'show']);

    // Pengaduan
    Route::get('pengaduan', [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('pengaduan/{id}', [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
    Route::delete('pengaduan/{id}', [AdminPengaduanController::class, 'destroy'])->name('pengaduan.destroy');
    Route::put('pengaduan/{id}/verifikasi', [AdminPengaduanController::class, 'verifikasi'])->name('pengaduan.verifikasi');

    // Prestasi
    Route::resource('prestasi', AdminPrestasiController::class);
    Route::get('prestasi/cari-mahasiswa', [AdminPrestasiController::class, 'cariMahasiswa'])->name('prestasi.cariMahasiswa');

    // Sanksi
    Route::resource('sanksi', AdminSanksiController::class);

    // =====================================================
    // SPK MANAGEMENT
    // =====================================================

    Route::prefix('spk')->name('spk.')->group(function () {

        // LEVEL 1 — KEPUTUSAN (Menggunakan KeputusanController)
        Route::controller(KeputusanController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{idKeputusan}/edit', 'edit')->name('edit');
            Route::put('/{idKeputusan}', 'update')->name('update');
            Route::delete('/{idKeputusan}', 'destroy')->name('destroy');
        });

        // LEVEL 2 — KRITERIA (Menggunakan KriteriaController)
        Route::controller(KriteriaController::class)->prefix('{idKeputusan}/kriteria')->name('kriteria.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{idKriteria}/edit', 'edit')->name('edit');
            Route::put('/{idKriteria}', 'update')->name('update');
            Route::delete('/{idKriteria}', 'destroy')->name('destroy');

            // Route Subkriteria Index dipindahkan ke KriteriaController
            Route::get('/{idKriteria}/subkriteria', 'subkriteriaIndex')->name('subkriteria.index');
        });

        // LEVEL 3 — ALTERNATIF (Menggunakan AlternatifController)
        Route::controller(AlternatifController::class)->prefix('{idKeputusan}/alternatif')->name('alternatif.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{idAlternatif}/edit', 'edit')->name('edit');
            Route::put('/{idAlternatif}', 'update')->name('update');
            Route::delete('/{idAlternatif}', 'destroy')->name('destroy');
        });

        // LEVEL 4 — PENILAIAN (Menggunakan PenilaianController)
        Route::controller(PenilaianController::class)->prefix('{idKeputusan}/penilaian')->name('penilaian.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/', 'update')->name('update');
        });

        // LEVEL 5 — SUBKRITERIA
        Route::controller(SubkriteriaController::class)
            ->prefix('{idKeputusan}/kriteria/{idKriteria}/subkriteria')
            // Ganti nama yang panjang dan duplikat, cukup gunakan:
            ->name('kriteria.subkriteria.') // Nama akan menjadi: admin.spk.kriteria.subkriteria.
            ->group(function () {
                Route::get('/', 'index')->name('index'); // Nama lengkap: admin.spk.kriteria.subkriteria.index
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{idSubKriteria}/edit', 'edit')->name('edit');
                Route::put('/{idSubKriteria}', 'update')->name('update');
                Route::delete('/{idSubKriteria}', 'destroy')->name('destroy');
            });

        // LEVEL 6 — HASIL (Menggunakan SpkManagementController)
        Route::get('{idKeputusan}/hasil', [SpkManagementController::class, 'showHasilAkhir'])->name('hasil');

        // =====================================================
        // PERBANDINGAN KRITERIA (AHP) - DITAMBAHKAN
        // =====================================================
        Route::controller(PerbandinganKriteriaController::class)->prefix('{idKeputusan}/perbandingan')->name('perbandingan.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/save', 'save')->name('simpan');
            Route::post('/check', 'checkConsistency')->name('cek_konsistensi');
        });

        // =====================================================
        // PERHITUNGAN SPK AKHIR (SAW/MOORA/DLL) - DIKOREKSI/DIPINDAHKAN
        // =====================================================
        Route::get('{idKeputusan}/run-calculation', [SpkCalculationController::class, 'runCalculation'])->name('run.calculation');

        // Route untuk tampilan proses perhitungan (jika ada)
        Route::prefix('{idKeputusan}/calculate')->name('calculate.')->group(function () {
            Route::get('proses', [SpkCalculationController::class, 'showPerhitungan'])->name('proses');
        });

    });

});


// =========================================================
// ROUTE — PENGURUS
// =========================================================
Route::prefix('pengurus')->name('pengurus.')
    ->middleware(['auth', 'role:pengurus'])
    ->group(function () {

        Route::get('/dashboard', [PengurusDashboardController::class, 'index'])->name('dashboard');

        Route::resource('divisi', PengurusDivisiController::class)->parameters(['divisi' => 'id_divisi']);
        Route::resource('jabatan', \App\Http\Controllers\Pengurus\JabatanController::class);
        Route::resource('pengurus', \App\Http\Controllers\Pengurus\PengurusController::class);
        Route::resource('keuangan', \App\Http\Controllers\Pengurus\KeuanganController::class);

    });


// =========================================================
// ROUTE — USER
// =========================================================
Route::prefix('user')->name('user.')
    ->middleware(['auth', 'role:user'])
    ->group(function () {

        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::resource('berita', UserBeritaController::class);
        Route::resource('pengaduan', UserPengaduanController::class);

    });


// =========================================================
// LOGIN GOOGLE + PROFIL
// =========================================================

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/komentar/{komentar}', [PublicKomentarController::class, 'update'])->name('komentar.update');
    Route::delete('/komentar/{komentar}', [PublicKomentarController::class, 'destroy'])->name('komentar.destroy');
});