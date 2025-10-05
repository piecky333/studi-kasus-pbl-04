<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\KontenController;

Route::get('/', function () {
    return view('public.home');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard');
});

Route::resource('berita', KontenController::class);