<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\berita; 
use App\Models\laporan\pengaduan; 
use Illuminate\Support\Facades\DB; 

/**
 * Class DashboardController
 * 
 * Controller ini bertanggung jawab untuk menampilkan halaman Dashboard Admin.
 * Halaman ini menyajikan ringkasan statistik, grafik, dan notifikasi penting
 * untuk memberikan gambaran umum sistem kepada administrator.
 * 
 * @package App\Http\Controllers\Admin
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     * 
     * Mengumpulkan berbagai metrik data:
     * 1. Total User (Mahasiswa) dan Pengurus.
     * 2. Statistik Berita dan Pengaduan.
     * 3. Data untuk Grafik Garis (Tren Laporan per Bulan).
     * 4. Data untuk Grafik Pie (Distribusi Status Laporan).
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        \Illuminate\Support\Facades\Log::info('Admin Dashboard Hit. User: ' . auth()->user()->id . ' Role: ' . auth()->user()->role);
        
        // 1. MENGAMBIL DATA STATISTIK UTAMA (CARD)
        // Menghitung jumlah record untuk ditampilkan di kartu ringkasan dashboard.
        $totalUser = User::where('role', 'user')->count(); // Mahasiswa
        $totalPengurus = User::where('role', 'pengurus')->count();
        $totalBerita = berita::count();
        $totalPengaduan = pengaduan::count(); 

        // Mengambil Berita yang membutuhkan perhatian (Status Pending).
        // Ini membantu admin untuk segera memverifikasi berita baru.
        $beritaPending = berita::where('status', 'pending')->latest()->get();

        // Mengambil 5 Pengaduan Terbaru dengan status 'Diproses' untuk ditampilkan di widget "Aktivitas Terbaru".
        $recentPengaduan = pengaduan::where('status', 'Diproses')->latest()->take(5)->get();

        // 2A. PERSIAPAN DATA LINE CHART: Tren Laporan per Bulan
        // Mengelompokkan data pengaduan berdasarkan bulan pembuatan untuk tahun berjalan.
        $laporanPerBulan = pengaduan::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as jumlah')
        )
        ->whereYear('created_at', date('Y')) // Hanya ambil data tahun ini
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

        // 2B. PERSIAPAN DATA PIE CHART: Distribusi Status Laporan
        // Menghitung jumlah laporan berdasarkan statusnya (Pending, Proses, Selesai, Ditolak).
        $statusLaporan = pengaduan::select('status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status')
            ->get();

        // 3A. FORMAT DATA LINE CHART (Array 12 Bulan)
        // Inisialisasi array dengan 0 untuk setiap bulan (Jan-Des).
        // Kemudian isi dengan data aktual dari database. Ini memastikan grafik tetap utuh meski ada bulan kosong.
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $data = array_fill(0, 12, 0);

        foreach ($laporanPerBulan as $laporan) {
            if ($laporan->bulan >= 1 && $laporan->bulan <= 12) {
                $data[$laporan->bulan - 1] = $laporan->jumlah;
            }
        }

        // 3B. FORMAT DATA PIE CHART
        // Memisahkan label dan data numerik ke dalam dua array terpisah sesuai format library Chart.js.
        $pieLabels = [];
        $pieData = [];
        
        foreach ($statusLaporan as $status) {
            $pieLabels[] = ucfirst($status->status ?? 'Tidak Diketahui');
            $pieData[] = $status->jumlah;
        }

        // 4. RENDER VIEW
        // Mengirimkan semua variabel yang telah disiapkan ke view dashboard.
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