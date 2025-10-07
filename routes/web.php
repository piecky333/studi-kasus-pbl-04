<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\KontenController;
use App\Http\Controllers\JabatanController;

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

// Route::post('/laporan', [App\Http\Controllers\LaporanController::class, 'store'])->name('laporan.store');
Route::resource('berita', KontenController::class);
Route::resource('jabatan', JabatanController::class);