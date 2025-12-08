<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Admin\Divisi;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman utama publik.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil 3 berita kegiatan terbaru (verified).
        $kegiatanTerbaru = Berita::where('kategori', 'kegiatan')
            ->where('status', 'verified')
            ->latest()
            ->take(3)
            ->get();

        // Ambil 3 berita prestasi terbaru (verified).
        $prestasiTerbaru = Berita::where('kategori', 'prestasi')
            ->where('status', 'verified')
            ->latest()
            ->take(3)
            ->get();

        // Ambil semua divisi.
        $divisi = Divisi::all();

        // Kirim data ke view.
        return view('pages.public.welcome', compact(
            'kegiatanTerbaru',
            'prestasiTerbaru',
            'divisi'
        ));
    }
}
