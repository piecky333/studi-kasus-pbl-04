<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;

class PrestasiController extends Controller
{
    /**
     * Tampilkan daftar prestasi.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua prestasi terbaru (paginasi 9).
        $semuaPrestasi = berita::where('kategori', 'prestasi')
            ->latest()
            ->paginate(9);

        return view('pages.public.prestasi.index', compact('semuaPrestasi'));

    }

    /**
     * Tampilkan detail prestasi.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $berita = berita::where('kategori', 'prestasi')
            ->findOrFail($id);

        $beritaTerkait = berita::where('kategori', 'prestasi')
            ->where('id_berita', '!=', $id)
            ->latest()->take(3)->get();

        // Ambil komentar induk dan balasan.
        $komentar_induk = $berita->komentar()
            ->whereNull('parent_id')
            ->with(['replies.parent'])
            ->latest()
            ->get();

        // Kirim data ke view.
        return view('pages.public.prestasi.show', compact(
            'berita',
            'beritaTerkait',
            'komentar_induk'
        ));
    }
}
