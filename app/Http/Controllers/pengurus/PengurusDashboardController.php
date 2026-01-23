<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Admin\Divisi;
use App\Models\Jabatan;
use App\Models\User;
use App\Models\Berita; 
use Illuminate\Support\Facades\Auth;

class PengurusDashboardController extends Controller
{
    /**
     * Tampilkan dashboard pengurus.
     * Hitung statistik dan tampilkan berita terbaru.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $totalDivisi   = Divisi::count();
        $totalJabatan  = Jabatan::count();
        $totalPengurus = User::where('role', 'pengurus')->count();
        
        // Fetch recent news created by the logged-in user
        $recentBerita = berita::where('id_user', Auth::id())
                              ->latest()
                              ->take(5)
                              ->get();

        return view('pages.pengurus.dashboard', compact(
            'totalDivisi',
            'totalJabatan',
            'totalPengurus',
            'recentBerita'
        ));
    }
}
