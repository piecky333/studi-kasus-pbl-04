<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\KontenController;
use App\Http\Controllers\JabatanController;

Route::get('/', function () {
    return view('layouts.public');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard');
});

Route::resource('berita', KontenController::class);
Route::resource('jabatan', JabatanController::class);