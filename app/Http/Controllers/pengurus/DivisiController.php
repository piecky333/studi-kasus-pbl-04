<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index()
    {
        $divisi = Divisi::orderBy('created_at', 'desc')->get();
        return view('pages.pengurus.divisi.index', compact('divisi'));
    }

    public function show(Divisi $divisi)
    {
        return view('pages.pengurus.divisi.show', compact('divisi'));
    }
}
