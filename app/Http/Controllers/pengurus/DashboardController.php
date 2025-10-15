<?php

namespace App\Http\Controllers\pengurus;

use App\Http\Controllers\Controller;
use App\Models\admin\Divisi;
use App\Models\Jabatan;
use App\Models\admin\Keuangan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDivisi = Divisi::count();
        $totalJabatan = Jabatan::count();
        $totalPengurus = User::where('role', 'pengurus')->count();
        $totalKeuangan = Keuangan::sum('jumlah_iuran');

        return view('pages.pengurus.dashboard', compact(
            'totalDivisi',
            'totalJabatan',
            'totalPengurus',
            'totalKeuangan'
        ));
    }
}
