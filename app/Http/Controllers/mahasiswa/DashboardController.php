<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama untuk Mahasiswa.
     * 
     * Dashboard ini memberikan ringkasan informasi penting:
     * 1. Berita kegiatan terbaru (Verified items only).
     * 2. Riwayat pengaduan terakhir yang dibuat oleh mahasiswa.
     * 3. Riwayat sanksi yang pernah diterima (jika ada).
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil user login
        $user = Auth::user();
        
        // Ambil 4 berita kegiatan terbaru (verified)
        $beritaTerbaru = Berita::where('kategori', 'kegiatan')
            ->where('status', 'verified')
            ->latest()
            ->take(4)
            ->get();

        // Ambil Pengaduan User
        $pengaduan = $user->pengaduan()->latest()->take(5)->get();

        // Ambil Data Sanksi.
        // Logika: Mencari profile 'mahasiswa' berdasarkan email user login.
        // Jika ditemukan, ambil sanksi yang berelasi dengan ID mahasiswa tersebut.
        $sanksi = collect([]);
        
        $mahasiswaProfile = \App\Models\Admin\Datamahasiswa::where('email', $user->email)
            ->orWhere('nim', $user->username)
            ->first();
        
        if ($mahasiswaProfile) {
            $sanksi = \App\Models\Admin\Sanksi::where('id_mahasiswa', $mahasiswaProfile->id_mahasiswa)
                ->latest()
                ->get();
        }

        return view('pages.mahasiswa.dashboard', compact('beritaTerbaru', 'pengaduan', 'sanksi'));
    }
}
