<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Pengurus;
use App\Models\Berita;
use Illuminate\Http\Request;

class PengurusDashboardController extends Controller
{
    public function index()
    {
        $totalDivisi = Divisi::count();
        $totalJabatan = Jabatan::count();
        $totalPengurus = Pengurus::count();
        
        $recentBerita = Berita::where('id_user', auth()->user()->id_user)
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
