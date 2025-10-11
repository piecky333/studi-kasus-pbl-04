<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\Admin\PrestasiController;

// Public routes
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

// Auth routes (override Breeze)
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Protected routes by role
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard'); // Assume view exists or create later
    })->name('admin.dashboard');

    Route::resource('berita', BeritaController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('prestasi', PrestasiController::class)->except(['show']);
});

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', function () {
        return view('mahasiswa.dashboard'); // Assume view exists
    })->name('mahasiswa.dashboard');
});

// Fallback dashboard
Route::get('/dashboard', function () {
    return view('pages.dashboard');
});

