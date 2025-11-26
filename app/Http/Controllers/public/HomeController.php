<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Admin\Divisi;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (home) publik
     */
    public function index()
    {
        // Ambil 3 berita kegiatan terbaru yang sudah diverifikasi
        $kegiatanTerbaru = Berita::where('kategori', 'kegiatan')
            ->where('status', 'verified') // hanya yang sudah disetujui admin
            ->latest()
            ->take(3)
            ->get();

        // Ambil 3 berita prestasi terbaru yang sudah diverifikasi
        $prestasiTerbaru = Berita::where('kategori', 'prestasi')
            ->where('status', 'verified') // hanya yang sudah disetujui admin
            ->latest()
            ->take(3)
            ->get();

        // Ambil semua divisi
        $divisi = Divisi::all();

        // Kirim data ke view
        return view('pages.public.welcome', compact(
            'kegiatanTerbaru',
            'prestasiTerbaru',
            'divisi'
        ));
    }
}
