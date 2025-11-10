<?php

namespace App\Http\Controllers\public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\divisi;

class DivisiController extends Controller
{
    public function index()
    {
        // Ambil semua data divisi, mungkin diurutkan berdasarkan nama
        $semuaDivisi = divisi::orderBy('nama_divisi')->get(); 

        return view('public.divisi.index', compact('semuaDivisi')); 
        // Anda perlu membuat view 'public.divisi.index'
    }

    /**
     * Menampilkan halaman detail satu Divisi.
     * $slugOrId bisa berupa ID atau slug (nama unik ramah URL)
     */
    public function show($slugOrId) 
    {
        // Cari divisi berdasarkan ID atau slug
        // Contoh jika menggunakan slug:
        // $divisi = Divisi::where('slug', $slugOrId)->firstOrFail(); 
        $divisi = divisi::findOrFail($slugOrId); // Contoh jika menggunakan ID

        return view('public.divisi.show', compact('divisi'));
        // Anda perlu membuat view 'public.divisi.show'
    }
}
