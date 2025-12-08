<?php

namespace App\Http\Controllers\user; 

use App\Http\Controllers\Controller; 
use App\Models\Berita;
use App\Models\laporan\pengaduan; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard user.
     * Hitung statistik pengaduan dan tampilkan berita terbaru.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil user login.
        $user = Auth::user();

        // Ambil semua pengaduan user.
        $semuaPengaduanUser = $user->pengaduan()->get();

        // Hitung total dan status pengaduan.
        $totalPengaduan = $semuaPengaduanUser->count();
        $pengaduanDiproses = $semuaPengaduanUser->where('status', 'Diproses')->count();
        $pengaduanSelesai = $semuaPengaduanUser->where('status', 'Selesai')->count();

        $pengaduanTerakhir = $user->pengaduan()->latest()->take(5)->get();

        // Ambil 4 berita kegiatan terbaru (verified).
        $beritaTerbaru = Berita::where('kategori', 'kegiatan')
            ->where('status', 'verified')
            ->latest()
            ->take(4)
            ->get();

        // Kirim data ke view.
        return view('pages.user.dashboard', compact(
            'totalPengaduan',
            'pengaduanDiproses',
            'pengaduanSelesai',
            'pengaduanTerakhir',
            'beritaTerbaru'    
        ));
    }
}
