<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\KontenController;
use App\Http\Controllers\JabatanController;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('berita', KontenController::class);
Route::resource('jabatan', JabatanController::class);