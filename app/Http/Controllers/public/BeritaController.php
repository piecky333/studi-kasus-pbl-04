<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;

class BeritaController extends Controller
{
    /**
     * Menampilkan halaman DAFTAR SEMUA berita/kegiatan yang sudah diverifikasi.
     */
    public function index()
    {
        $daftarKegiatan = Berita::where('kategori', 'kegiatan')
            ->where('status', 'verified') // hanya berita yang sudah diverifikasi
            ->latest()
            ->paginate(9);

        return view('pages.public.berita.index', compact('daftarKegiatan'));
    }

    /**
     * Menampilkan halaman DETAIL SATU berita yang sudah diverifikasi.
     */
    public function show($id)
    {
        // Ambil berita satuan
        $berita = Berita::where('kategori', 'kegiatan')
            ->where('status', 'verified') // filter verified
            ->findOrFail($id);

        // Ambil berita terkait (3 berita terbaru, selain yang ini)
        $beritaTerkait = Berita::where('kategori', 'kegiatan')
            ->where('status', 'verified') // filter verified
            ->where('id_berita', '!=', $id)
            ->latest()
            ->take(3)
            ->get();

        // Ambil komentar induk beserta balasannya
        $komentar_induk = $berita->komentar()
            ->whereNull('parent_id')
            ->with(['replies.parent'])
            ->latest()
            ->get();

        return view('pages.public.berita.show', compact(
            'berita',
            'beritaTerkait',
            'komentar_induk'
        ));
    }
}
