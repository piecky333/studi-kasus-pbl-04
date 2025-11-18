<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\SpkController; // Commented out as SpkController does not exist

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/spk/ranking', [SpkController::class, 'getRanking']); // Commented out as SpkController does not exist
