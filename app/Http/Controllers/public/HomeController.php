<?php

namespace App\Http\Controllers\public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\berita;
use App\Models\Admin\Divisi;

class HomeController extends Controller
{
    public function index()
    {
        $kegiatanTerbaru = berita::where('kategori', 'kegiatan')
            ->latest()
            ->take(3)
            ->get();

        $prestasiTerbaru = berita::where('kategori', 'prestasi')
            ->latest()
            ->take(3)
            ->get();

        $divisi = Divisi::all();  

        return view('pages.public.welcome', compact(
            'kegiatanTerbaru',
            'prestasiTerbaru',
            'divisi'
        ));
    }
}
