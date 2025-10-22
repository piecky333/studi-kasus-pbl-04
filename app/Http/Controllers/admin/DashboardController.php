<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; 
use App\Models\berita; 
use App\Models\laporan\pengaduan; 

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin dengan data dinamis.
     */
    public function index()
    {
        // 1. Mengambil data User (hanya yang rolenya 'user')
        $totalUser = User::where('role', 'user')->count();

        // 2. Mengambil data Anggota (asumsi 'anggota' adalah user dengan role 'pengurus')
        $totalPengurus = User::where('role', 'pengurus')->count();

        // 3. Mengambil total Berita
        $totalBerita = berita::count();

        // 4. Mengambil total Laporan (asumsi 'Laporan' adalah 'Pengaduan')
        $totalLaporan = pengaduan::count();

        // Mengirim semua data ke view
        return view('pages.dashboard', [
            'totalUser' => $totalUser,
            'totalPengurus' => $totalPengurus,
            'totalBerita' => $totalBerita,
            'totalLaporan' => $totalLaporan,
        ]);
    }
}
