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
use App\Http\Controllers\Spk\{
    KeputusanController,
    KriteriaController,
    AlternatifController,
    PenilaianController,
    SubKriteriaController,
    PerbandinganKriteriaController,
    PerhitunganSAWController
};

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

    // Berita
    Route::get('/berita', [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/create', [AdminBeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}/edit', [AdminBeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [AdminBeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [AdminBeritaController::class, 'destroy'])->name('berita.destroy');
    Route::put('/berita/{id}/verifikasi', [AdminBeritaController::class, 'verifikasi'])->name('berita.verifikasi');
    Route::put('/berita/{id}/tolak', [AdminBeritaController::class, 'tolak'])->name('berita.tolak');

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

        // LEVEL 1: DAFTAR KEPUTUSAN (CRUD UTAMA)
        Route::get('/', [KeputusanController::class, 'index'])->name('index');
        Route::get('/create', [KeputusanController::class, 'create'])->name('create');
        Route::post('/', [KeputusanController::class, 'store'])->name('store');
        Route::get('/{idKeputusan}/edit', [KeputusanController::class, 'edit'])->name('edit');
        Route::put('/{idKeputusan}', [KeputusanController::class, 'update'])->name('update');
        Route::delete('/{idKeputusan}', [KeputusanController::class, 'destroy'])->name('destroy');

        // LEVEL 2: DETAIL KEPUTUSAN (Navigasi Tab)
        Route::prefix('{idKeputusan}')->group(function () {

            // TAB 1: KRITERIA & BOBOT 
            Route::prefix('kriteria')->name('kriteria.')->group(function () {

                // KOREKSI: MENGGANTI Route::resource DENGAN RUTE EKSPLISIT UNTUK MENGHINDARI KONFLIK
                Route::controller(KriteriaController::class)->group(function () {
                    // Index (GET /kriteria) - PINTU MASUK TAB & LIST
                    Route::get('/', 'index')->name('index');
                    // CRUD Kriteria
                    Route::get('/create', 'create')->name('create');
                    Route::post('/', 'store')->name('store');
                    Route::get('/{idKriteria}/edit', 'edit')->name('edit');
                    Route::put('/{idKriteria}', 'update')->name('update');
                    Route::delete('/{idKriteria}', 'destroy')->name('destroy');
                });

                // Subkriteria (Nested Resource)
                Route::name('subkriteria.')->controller(SubKriteriaController::class)->group(function () {

                    // BASE PATH (misalnya, /kriteria/{idKriteria}/subkriteria)
                    $basePath = '/{idKriteria}/subkriteria';

                    // 1. INDEX: GET /kriteria/{idKriteria}/subkriteria
                    Route::get($basePath, 'index')->name('index');

                    // 2. CREATE: GET /kriteria/{idKriteria}/subkriteria/create
                    Route::get($basePath . '/create', 'create')->name('create');

                    // 3. STORE: POST /kriteria/{idKriteria}/subkriteria
                    Route::post($basePath, 'store')->name('store');

                    // 4. EDIT: GET /kriteria/{idKriteria}/subkriteria/{subkriteriumId}/edit
                    Route::get($basePath . '/{subkriteriumId}/edit', 'edit')->name('edit');

                    // 5. UPDATE: PUT /kriteria/{idKriteria}/subkriteria/{subkriteriumId}
                    Route::put($basePath . '/{subkriteriumId}', 'update')->name('update');

                    // 6. DESTROY: DELETE /kriteria/{idKriteria}/subkriteria/{subkriteriumId}
                    Route::delete($basePath . '/{subkriteriumId}', 'destroy')->name('destroy');
                });

                // Perbandingan Kriteria (AHP)
                Route::controller(PerbandinganKriteriaController::class)->prefix('perbandingan')->name('perbandingan.')->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/save', 'save')->name('simpan');
                    Route::post('/check', 'checkConsistency')->name('cek_konsistensi');
                });
            });

            // TAB 2: ALTERNATIF & PENILAIAN
            Route::prefix('alternatif')->name('alternatif.')->group(function () {

                // KOREKSI: MENGGANTI Route::resource DENGAN RUTE EKSPLISIT UNTUK MENGHINDARI KONFLIK
                Route::controller(AlternatifController::class)->group(function () {
                    // Index (GET /alternatif) - PINTU MASUK TAB & LIST
                    Route::get('/', 'index')->name('index');
                    // CRUD Alternatif
                    Route::get('/create', 'create')->name('create');
                    Route::post('/', 'store')->name('store');
                    Route::get('/{idAlternatif}/edit', 'edit')->name('edit');
                    Route::put('/{idAlternatif}', 'update')->name('update');
                    Route::delete('/{idAlternatif}', 'destroy')->name('destroy');
                });

                // Penilaian Alternatif ($Xij$)
                Route::controller(PenilaianController::class)->prefix('nilai')->name('penilaian.')->group(function () {
                    Route::get('/', 'index')->name('index'); // Menampilkan matriks penilaian semua alternatif
                    Route::put('/', 'update')->name('update'); // Menyimpan semua nilai penilaian
                });
            });

            // TAB 3: HASIL & PERHITUNGAN
            Route::prefix('hasil')->name('hasil.')->group(function () {
                // Tampilan Hasil Akhir (Tab)
                Route::get('/', [PerhitunganSAWController::class, 'index'])->name('index');

                // Trigger Perhitungan Ulang (SAW)
                Route::post('/run', [PerhitunganSAWController::class, 'runCalculation'])->name('run');
            });

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
        Route::resource('berita', \App\Http\Controllers\Pengurus\BeritaController::class);
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