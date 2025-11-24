<?php

namespace App\Http\Controllers\Spk;

use App\Models\hasilakhir;
use App\Services\SawService;
use Illuminate\Http\Request;
use App\Http\Controllers\Spk\KeputusanDetailController; // PENTING: Import Base Controller SPK

/**
 * Controller ini bertanggung jawab untuk logika bisnis Hasil Akhir (Tab Hasil).
 * Termasuk menampilkan hasil ranking dan memicu perhitungan ulang SAW.
 * Mewarisi KeputusanDetailController untuk akses Keputusan induk ($this->keputusan).
 */
class PerhitunganSAWController extends KeputusanDetailController
{
    protected SawService $sawService;

    /**
     * Constructor menginjeksikan SAW Service dan memuat data Keputusan induk.
     * @param Request $request
     * @param SawService $sawService
     */
    public function __construct(Request $request, SawService $sawService)
    {
        // Panggil constructor parent untuk memuat Keputusan dan $this->idKeputusan
        parent::__construct($request);
        $this->sawService = $sawService;
    }

    /**
     * Menampilkan halaman Hasil Akhir (Tab Hasil) dan detail proses perhitungan.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Ambil hasil ranking yang sudah tersimpan dari database (Tampilan Final)
        // KOREKSI: Gunakan whereHas untuk memfilter HasilAkhir melalui relasi 'alternatif' 
        // yang terikat dengan $this->idKeputusan.
        $rankingResults = HasilAkhir::whereHas('alternatif', function ($query) {
            $query->where('id_keputusan', $this->idKeputusan);
        })
            ->with('alternatif') // Memuat relasi Alternatif untuk nama/detail
            ->orderBy('rangking', 'asc')
            ->get();
            
        // 2. Ambil data perhitungan lengkap (jika perlu ditampilkan detail proses)
        $calculationData = null;
        $isReady = true; // Flag untuk mengontrol apakah tombol 'Hitung' boleh ditekan
        
        try {
            // Memuat data proses (Matriks, Normalisasi, dll)
            // Method ini akan melempar Exception jika data Kriteria/Alternatif tidak lengkap.
            $calculationData = $this->sawService->calculateProcessData($this->idKeputusan);
        } catch (\Exception $e) {
            // Jika ada exception (data tidak lengkap/tidak konsisten AHP)
            $isReady = false;
            // session()->now digunakan agar pesan error hanya muncul saat halaman dimuat
            session()->now('error', $e->getMessage()); 
        }

        return view('pages.admin.spk.hasil.index', [
            'idKeputusan' => $this->idKeputusan,
            'keputusan' => $this->keputusan,
            'rankingResults' => $rankingResults,
            'calculationData' => $calculationData,
            'isReady' => $isReady 
        ]);
    }

    /**
     * Memicu proses perhitungan SAW dan menyimpan hasilnya ke tabel HasilAkhir.
     * Dipanggil melalui POST route: admin.spk.hasil.run.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function runCalculation()
    {
        // NOTE: Request object tidak perlu di-type hint jika tidak digunakan,
        // tetapi dipertahankan jika Anda ingin menambahkan validasi isMethod('POST') 
        // seperti yang disarankan di langkah sebelumnya.

        try {
            // Panggil Service untuk menjalankan proses hitung dan simpan
            $this->sawService->executeAndSaveResult($this->idKeputusan);
            
            // Perbarui status keputusan (Status: Draft -> Selesai/Aktif)
            $this->keputusan->status = 'Selesai';
            $this->keputusan->save();

            $message = 'Perhitungan SAW berhasil dijalankan dan hasil ranking telah disimpan! Keputusan sekarang: ' . $this->keputusan->status;
            session()->flash('success', $message);

        } catch (\Exception $e) {
            // Tangkap exception jika data tidak valid/tidak lengkap
            session()->flash('error', "Gagal melakukan perhitungan SAW: " . $e->getMessage());
        }

        // Menggunakan PRG Pattern: Redirect kembali ke halaman Hasil Akhir (GET route)
        return redirect()->route('admin.spk.hasil.index', $this->idKeputusan);
    }
}