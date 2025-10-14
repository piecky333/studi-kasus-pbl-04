<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\admin\PengaduanController as AdminPengaduanController;
use App\Http\Controllers\pengurus\DivisiController as PengurusDivisiController;
use App\Http\Controllers\admin\DivisiController as AdminDivisiController;

Route::get('/', function () {
    return view('public.home');
});

Route::get('/divisi', function () {
    return view('public.divisi');
});

Route::get('/profile', function () {
    return view('public.profile');
});

Route::get('/berita', function () {
    return view('public.berita.index');
});

Route::get('/prestasiMahasiswa', function () {
    return view('public.prestasi');
});

Route::get('/laporan', function () {
    return view('public.laporan');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard');
});
Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('/berita', [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/create', [AdminBeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}/edit', [AdminBeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [AdminBeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [AdminBeritaController::class, 'destroy'])->name('berita.destroy');
});


// 
Route::resource('berita', BeritaController::class);
Route::resource('jabatan', JabatanController::class);
route::resource('pengaduan',PengaduanController::class);

// Admin pengaduan
Route::get('/admin/pengaduan', [AdminPengaduanController::class, 'index']);
Route::get('/admin/pengaduan/{id}', [AdminPengaduanController::class, 'show']);
Route::put('/admin/pengaduan/{id}/verifikasi', [AdminPengaduanController::class, 'verifikasi']);
Route::delete('/admin/pengaduan/{id}', [AdminPengaduanController::class, 'destroy']);

Route::prefix('pengurus')->name('pengurus.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.pengurus.dashboard');
    })->name('dashboard');

    // CRUD lengkap divisi
    Route::resource('divisi', PengurusDivisiController::class);
});

/*
|--------------------------------------------------------------------------
| ROUTE UNTUK ADMIN (READ-ONLY)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.admin.dashboard');
    })->name('dashboard');

    // hanya index & show
    Route::get('divisi', [AdminDivisiController::class, 'index'])->name('divisi.index');
    Route::get('divisi/{id}', [AdminDivisiController::class, 'show'])->name('divisi.show');
});