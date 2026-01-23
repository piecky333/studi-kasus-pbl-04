<?php

namespace App\Http\Controllers\User; 

use App\Http\Controllers\Controller; 
use App\Models\Berita;
use App\Models\Laporan\Pengaduan; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama untuk pengguna (User).
     * 
     * Halaman ini menyajikan:
     * 1. Ringkasan statistik pengaduan user (Total, Diproses, Selesai).
     * 2. Riwayat singkat pengaduan yang pernah dibuat.
     * 3. Berita kegiatan terbaru dari kampus.
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

        $pengaduanTerakhir = $user->pengaduan()->with('tanggapan')->latest()->take(5)->get();

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

    /**
     * Menandai notifikasi sebagai sudah dibaca (Mark as Read).
     * 
     * Setelah ditandai, pengguna akan diarahkan ke URL yang relevan 
     * dengan notifikasi tersebut.
     * 
     * @param  string  $id  ID Notifikasi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return redirect($notification->data['url']);
        }

        return back();
    }
}
