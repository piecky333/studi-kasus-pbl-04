<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;

class BeritaController extends Controller
{
    /**
     * Tampilkan daftar berita kegiatan yang terverifikasi.
     *
     * @return \Illuminate\View\View
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
     * Tampilkan detail berita kegiatan.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Ambil berita verified.
        $berita = Berita::where('kategori', 'kegiatan')
            ->where('status', 'verified')
            ->findOrFail($id);

        // Ambil 3 berita terkait terbaru.
        $beritaTerkait = Berita::where('kategori', 'kegiatan')
            ->where('status', 'verified')
            ->where('id_berita', '!=', $id)
            ->latest()
            ->take(3)
            ->get();

        // Ambil komentar induk dan balasan.
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
