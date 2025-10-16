<?php

namespace App\Http\Controllers\pengurus;

use App\Http\Controllers\Controller;
use App\Models\admin\Divisi;
use App\Models\admin\Keuangan;
use App\Models\Jabatan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDivisi   = Divisi::count();
        $totalJabatan  = Jabatan::count();
        $totalPengurus = User::where('role', 'pengurus')->count();
        $totalKeuangan = Keuangan::where('status_pembayaran', 'lunas')->sum('jumlah_iuran');

        // statistik status pembayaran
        $belum  = Keuangan::where('status_pembayaran', 'belum')->count();
        $proses = Keuangan::where('status_pembayaran', 'proses')->count();
        $lunas  = Keuangan::where('status_pembayaran', 'lunas')->count();

        return view('pages.pengurus.dashboard', compact(
            'totalDivisi',
            'totalJabatan',
            'totalPengurus',
            'totalKeuangan',
            'belum',
            'proses',
            'lunas'
        ));
    }
}
