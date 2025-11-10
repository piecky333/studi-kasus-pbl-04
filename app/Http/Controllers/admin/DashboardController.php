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
        // 1. MENGAMBIL DATA STAT CARD (Logika dari tim Anda)
        $totalUser = User::where('role', 'user')->count();
        $totalPengurus = User::where('role', 'pengurus')->count();
        
        // DIUBAH: Menggunakan model tim Anda (huruf kecil)
        $totalBerita = berita::count();
        // DIUBAH: Menggunakan model tim Anda (huruf kecil)
        $totalPengaduan = pengaduan::count(); 

        // 2. MENGAMBIL DATA BARU UNTUK CHART
        // DIUBAH: Menggunakan model tim Anda (huruf kecil)
        $laporanPerBulan = pengaduan::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as jumlah')
        )
        ->whereYear('created_at', date('Y')) // Hanya ambil data tahun ini
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

        // 3. FORMAT DATA UNTUK CHART.JS
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $data = array_fill(0, 12, 0);

        foreach ($laporanPerBulan as $laporan) {
            if ($laporan->bulan >= 1 && $laporan->bulan <= 12) {
                $data[$laporan->bulan - 1] = $laporan->jumlah;
            }
        }

        // 4. KIRIM SEMUA DATA KE VIEW (Menggunakan path view tim Anda)
        return view('pages.admin.dashboard', [
            'totalUser' => $totalUser,
            'totalPengurus' => $totalPengurus,
            'totalBerita' => $totalBerita,
            // DIUBAH: Mengirimkan var $totalPengaduan sebagai $totalLaporan (sesuai view tim Anda)
            'totalLaporan' => $totalPengaduan, 
            'chartLabels' => $labels, // Data label untuk chart
            'chartData' => $data,   // Data angka untuk chart
        ]);
    }
}
