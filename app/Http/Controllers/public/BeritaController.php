<?php

namespace App\Http\Controllers\public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\berita; // Pastikan model berita di-import

class BeritaController extends Controller
{
    /**
     * Menampilkan halaman DAFTAR SEMUA berita/kegiatan.
     */
    public function index()
    {
        // Ini sudah benar
        $daftarKegiatan = berita::where('kategori', 'kegiatan')
            ->latest()
            ->paginate(9);

        return view('pages.public.berita.index', compact('daftarKegiatan'));
    }

    /**
     * Menampilkan halaman DETAIL SATU berita.
     * (INI BAGIAN YANG DIPERBAIKI)
     */
    public function show($id)
    {
        // 1. Ambil satu berita (lebih aman filter by kategori juga)
        $berita = berita::where('kategori', 'kegiatan')
            ->findOrFail($id);

        // 2. Ambil berita terkait (ini sudah benar)
        $beritaTerkait = berita::where('kategori', 'kegiatan') // diganti ke 'kegiatan'
            ->where('id_berita', '!=', $id)
            ->latest()
            ->take(3) 
            ->get();

        // 3. (PERBAIKAN) Ambil komentar induk DENGAN relasi balasannya
        $komentar_induk = $berita->komentar()
            ->whereNull('parent_id')
            ->with(['replies.parent']) 
            ->latest()
            ->get();

        // 4. (PERBAIKAN) Kirim SEMUA data ke view
        return view('pages.public.berita.show', compact(
            'berita',
            'beritaTerkait',
            'komentar_induk'
        ));
    }
}