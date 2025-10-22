<?php

namespace App\Http\Controllers\user;
use App\Http\Controllers\controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller

{
    public function index()
    {
        //mengambil data user yang sedang login
        $user = Auth::user();

        //menghitung jumlah pengaduan berdasarkan status
        $userPengaduan = $user->pengaduan(); 

        //menghitung total pengaduan dan berdasarkan status
        $totalPengaduan = $userPengaduan->count();
        $pengaduanDiproses = (clone $userPengaduan)->where('status', 'diproses')->count();
        $pengaduanSelesai = (clone $userPengaduan)->where('status', 'selesai')->count();
        $pengaduan = $user->pengaduan()->latest()->take(5)->get();
        $berita = Berita::latest()->take(5)->get();

        //mengirim data ke view dashboard user
        return view('user.dashboard', compact(
            'totalPengaduan',
            'pengaduanDiproses',
            'pengaduanSelesai',
            'pengaduan',
            'berita'
        ));
    }
}