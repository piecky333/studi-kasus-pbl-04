<?php

require __DIR__ . '/auth.php';

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleAuthController;

// ===========================
// CONTROLLERS (GROUPED)
// ===========================

// PUBLIC
use App\Http\Controllers\public\{
    HomeController as PublicHomeController,
    DivisiController as PublicDivisiController,
    PrestasiController as PublicPrestasiController,
    BeritaController as PublicBeritaController,
    KomentarController as PublicKomentarController
};

// DEFAULT
use App\Http\Controllers\{
    BeritaController,
    JabatanController,
    PengaduanController
};

// USER
use App\Http\Controllers\user\{
    DashboardController as UserDashboardController,
    PengaduanController as UserPengaduanController,
    BeritaController as UserBeritaController
};

// ADMIN
use App\Http\Controllers\Admin\{
    BeritaController as AdminBeritaController,
    DivisiController as AdminDivisiController,
    PengaduanController as AdminPengaduanController,
    PrestasiController as AdminPrestasiController,
    DashboardController as AdminDashboardController,
    SanksiController as AdminSanksiController
};

// PENGURUS
use App\Http\Controllers\pengurus\{
    DivisiController as PengurusDivisiController,
    PengurusDashboardController
};

// =========================================================
// ROUTE PUBLIC
// =========================================================
Route::get('/', [PublicHomeController::class, 'index'])->name('home');

// --- Berita (public) ---
Route::get('/berita', [PublicBeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{berita}', [PublicBeritaController::class, 'show'])->name('berita.show');

// --- Divisi (public) ---
Route::get('/divisi', [PublicDivisiController::class, 'index'])->name('divisi.index');
Route::get('/divisi/{id}', [PublicDivisiController::class, 'show'])->name('divisi.show');

// --- Prestasi (public) ---
Route::get('/prestasi', [PublicPrestasiController::class, 'index'])->name('prestasi.index');
Route::get('/prestasi/{id}', [PublicPrestasiController::class, 'show'])->name('prestasi.show');

// --- Komentar (public) ---
Route::post('/berita/{id_berita}/komentar', [PublicKomentarController::class, 'store'])
    ->name('komentar.store');


// =========================================================
// ROUTE ADMIN
// =========================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Berita
    Route::get('/berita', [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/create', [AdminBeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}/edit', [AdminBeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [AdminBeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [AdminBeritaController::class, 'destroy'])->name('berita.destroy');

    // Pengurus (CRUD)
    Route::resource('pengurus', \App\Http\Controllers\Admin\PengurusController::class);

    // Divisi (admin)
    Route::get('/divisi', [AdminDivisiController::class, 'index'])->name('divisi.index');
    Route::get('/divisi/{id}', [AdminDivisiController::class, 'show'])->name('divisi.show');

    // Pengaduan
    Route::get('/pengaduan', [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{id}', [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
    Route::delete('/pengaduan/{id}', [AdminPengaduanController::class, 'destroy'])->name('pengaduan.destroy');
    Route::put('/pengaduan/{id}/verifikasi', [AdminPengaduanController::class, 'verifikasi'])->name('pengaduan.verifikasi');

    // Prestasi
    Route::resource('prestasi', AdminPrestasiController::class);
    Route::get('/prestasi/cari-mahasiswa', [AdminPrestasiController::class, 'cariMahasiswa'])
        ->name('prestasi.cariMahasiswa');

    // Sanksi
    Route::get('/sanksi', [AdminSanksiController::class, 'index'])->name('sanksi.index');
    Route::get('/sanksi/create', [AdminSanksiController::class, 'create'])->name('sanksi.create');
    Route::post('/sanksi', [AdminSanksiController::class, 'store'])->name('sanksi.store');
    Route::get('/sanksi/{id}/edit', [AdminSanksiController::class, 'edit'])->name('sanksi.edit');
    Route::put('/sanksi/{id}', [AdminSanksiController::class, 'update'])->name('sanksi.update');
    Route::delete('/sanksi/{id}', [AdminSanksiController::class, 'destroy'])->name('sanksi.destroy');
});


// =========================================================
// ROUTE PENGURUS
// =========================================================
Route::prefix('pengurus')->name('pengurus.')->middleware(['auth', 'role:pengurus'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [PengurusDashboardController::class, 'index'])->name('dashboard');

    // Divisi PENGURUS (PAKAI id_divisi)
    Route::resource('divisi', PengurusDivisiController::class)
        ->parameters(['divisi' => 'id_divisi']);

    // Jabatan
    Route::resource('jabatan', \App\Http\Controllers\Pengurus\JabatanController::class);

    // Pengurus
    Route::resource('pengurus', \App\Http\Controllers\Pengurus\PengurusController::class);

    // Keuangan
    Route::resource('keuangan', \App\Http\Controllers\Pengurus\KeuanganController::class);
});


// =========================================================
// ROUTE USER BIASA
// =========================================================
Route::prefix('user')->name('user.')->middleware(['auth', 'role:user'])->group(function () {

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::resource('berita', UserBeritaController::class);
    Route::resource('pengaduan', UserPengaduanController::class);
});


// =========================================================
// LOGIN GOOGLE
// =========================================================
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])
    ->name('google.redirect');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('google.callback');


// =========================================================
// ROUTE UMUM (PROFILE & KOMENTAR)
// =========================================================
Route::middleware('auth')->group(function () {

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Komentar update + delete
    Route::put('/komentar/{komentar}', [PublicKomentarController::class, 'update'])
        ->name('komentar.update');

    Route::delete('/komentar/{komentar}', [PublicKomentarController::class, 'destroy'])
        ->name('komentar.destroy');
});
