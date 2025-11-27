<?php

namespace App\Http\Controllers\user; 

use App\Http\Controllers\Controller; 
use App\Models\Berita;
use App\Models\laporan\pengaduan; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data user yang sedang login
        $user = Auth::user();

        // --- Sudut Pandang (Efisien) ---
        // Kita bisa ambil relasi sekali saja, lalu hitung
        $semuaPengaduanUser = $user->pengaduan()->get(); // Ambil Collection

        // Menghitung total pengaduan dan berdasarkan status dari Collection
        $totalPengaduan = $semuaPengaduanUser->count();

        // === KOREKSI CASE SENSITIVITY ===
        $pengaduanDiproses = $semuaPengaduanUser->where('status', 'Diproses')->count();
        $pengaduanSelesai = $semuaPengaduanUser->where('status', 'Selesai')->count();
        // ================================

        $pengaduanTerakhir = $user->pengaduan()->latest()->take(5)->get();

        // Ambil 4 berita global terakhir yang valid (kegiatan & verified)
        $beritaTerbaru = Berita::where('kategori', 'kegiatan')
            ->where('status', 'verified')
            ->latest()
            ->take(4)
            ->get();

        // Mengirim data ke view dashboard user
        return view('pages.user.dashboard', compact(
            'totalPengaduan',
            'pengaduanDiproses',
            'pengaduanSelesai',
            'pengaduanTerakhir',
            'beritaTerbaru'    
        ));
    }
}
