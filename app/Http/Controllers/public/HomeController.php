<?php

namespace App\Http\Controllers\public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\berita; 

class HomeController extends Controller
{
    public function index()
    {
        // 1. Ambil 3 Berita Kegiatan (Aktivitas, Seminar, Event) terbaru
        $kegiatanTerbaru = berita::where('kategori', 'kegiatan')
                                     ->latest() 
                                     ->take(3)
                                     ->get();
        
        // 2. Ambil 3 Berita Prestasi Mahasiswa terbaru
        $prestasiTerbaru = berita::where('kategori', 'prestasi')
                                     ->latest()
                                     ->take(3)
                                     ->get();

        // 3. Kirim kedua variabel tersebut ke view 'welcome.blade.php'
        return view('pages.public.welcome', compact('kegiatanTerbaru', 'prestasiTerbaru'));

    }
}