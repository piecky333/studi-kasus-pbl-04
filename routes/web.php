<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\KontenController;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('berita', KontenController::class);