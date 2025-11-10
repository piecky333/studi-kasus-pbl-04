<?php

namespace App\Http\Controllers\public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\berita;

class PrestasiController extends Controller
{
    public function index()
    {
        // Ambil semua prestasi, urutkan dari yang terbaru, bagi per halaman
        $semuaPrestasi = berita::where('kategori', 'prestasi')
            ->latest() // Urutkan dari yang terbaru
            ->paginate(9);

        return view('pages.public.prestasi.index', compact('semuaPrestasi'));

    }

    /**
     * Menampilkan halaman detail satu Prestasi.
     */
    // Di dalam file PrestasiController.php, method show($id)
    public function show($id)
    {
        $berita = berita::where('kategori', 'prestasi')
            ->findOrFail($id);

        $beritaTerkait = berita::where('kategori', 'prestasi')
            ->where('id_berita', '!=', $id)
            ->latest()->take(3)->get();

        // UBAH INI: Ambil HANYA komentar induk (parent_id = null)
        $komentar_induk = $berita->komentar()
            ->whereNull('parent_id')
            // Memuat balasan, DAN 'parent' dari balasan itu
            ->with(['replies.parent'])
            ->latest()
            ->get();

        // Kirim 'komentar_induk' BUKAN '$berita->komentar'
        return view('pages.public.prestasi.show', compact(
            'berita',
            'beritaTerkait',
            'komentar_induk'
        ));
    }
}
