<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\divisi;

class DivisiController extends Controller
{
    // 📋 Hanya lihat daftar divisi
    public function index()
    {
        $divisi = Divisi::orderBy('created_at', 'desc')->get();
        return view('pages.divisi.index', compact('divisi'));
    }

    // 👁️ Lihat detail divisi
    public function show($id)
    {
        $divisi = Divisi::findOrFail($id);
        return view('pages.divisi.show', compact('divisi'));
    }
}
