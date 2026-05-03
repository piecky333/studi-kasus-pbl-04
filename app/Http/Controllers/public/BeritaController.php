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
     * @param Berita $berita
     * @return \Illuminate\View\View
     */
    public function show(Berita $berita)
    {
        // Pastikan berita yang diakses adalah kategori kegiatan dan sudah verified
        if ($berita->kategori !== 'kegiatan' || $berita->status !== 'verified') {
            abort(404);
        }

        // Ambil 3 berita terkait terbaru.
        $beritaTerkait = Berita::where('kategori', 'kegiatan')
            ->where('status', 'verified')
            ->where('id_berita', '!=', $berita->id_berita)
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
