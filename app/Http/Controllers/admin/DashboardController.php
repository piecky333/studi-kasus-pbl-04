<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
// DIUBAH: Menggunakan path tim Anda (huruf kecil)
use App\Models\berita; 
// DIUBAH: Menggunakan path tim Anda (subfolder & huruf kecil)
use App\Models\laporan\pengaduan; 
use Illuminate\Support\Facades\DB; // Pastikan ini di-import

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     */
    public function index()
    {
        // 1. MENGAMBIL DATA STAT CARD
        $totalUser = User::where('role', 'user')->count(); // Mahasiswa
        $totalPengurus = User::where('role', 'pengurus')->count();
        $totalBerita = berita::count();
        $totalPengaduan = pengaduan::count(); 

        // Fetch News requiring attention
        $beritaPending = berita::where('status', 'pending')->latest()->get();

        // Fetch Recent Pengaduan
        $recentPengaduan = pengaduan::latest()->take(5)->get();

        // 2A. MENGAMBIL DATA UNTUK LINE CHART (Laporan per Bulan)
        $laporanPerBulan = pengaduan::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as jumlah')
        )
        ->whereYear('created_at', date('Y')) // Hanya ambil data tahun ini
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

        // 2B. MENGAMBIL DATA UNTUK PIE CHART (Status Laporan)
        $statusLaporan = pengaduan::select('status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status')
            ->get();

        // 3A. FORMAT DATA UNTUK LINE CHART
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $data = array_fill(0, 12, 0);

        foreach ($laporanPerBulan as $laporan) {
            if ($laporan->bulan >= 1 && $laporan->bulan <= 12) {
                $data[$laporan->bulan - 1] = $laporan->jumlah;
            }
        }

        // 3B. FORMAT DATA UNTUK PIE CHART
        $pieLabels = [];
        $pieData = [];
        
        foreach ($statusLaporan as $status) {
            $pieLabels[] = ucfirst($status->status ?? 'Tidak Diketahui');
            $pieData[] = $status->jumlah;
        }

        // 4. KIRIM SEMUA DATA KE VIEW
        return view('pages.admin.dashboard', [
            'totalUser' => $totalUser, // Representing Mahasiswa
            'totalPengurus' => $totalPengurus,
            'totalBerita' => $totalBerita,
            'totalLaporan' => $totalPengaduan, 
            'beritaPending' => $beritaPending,
            'recentPengaduan' => $recentPengaduan,
            'chartLabels' => $labels, 
            'chartData' => $data,   
            'pieLabels' => $pieLabels, 
            'pieData' => $pieData,   
        ]);
    }
}