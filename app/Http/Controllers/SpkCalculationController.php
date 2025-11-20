<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spkkeputusan;
use App\Services\SawService; 

class SpkCalculationController extends Controller
{
    protected SawService $sawService;

    public function __construct(SawService $sawService)
    {
        $this->sawService = $sawService;
    }

    /**
     * Menampilkan detail Matriks Perhitungan (Normalisasi & Pembobotan).
     */
    public function showPerhitungan($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        
        try {
            // Ambil semua data proses perhitungan dari Service
            $perhitunganData = $this->sawService->calculateProcessData($idKeputusan);

            return view('pages.admin.spk.perhitungan.index', [ // View baru
                'keputusan' => $keputusan,
                'data' => $perhitunganData,
                'pageTitle' => 'Proses Perhitungan SAW'
            ]);

        } catch (\Exception $e) {
            // Tangkap exception jika data tidak lengkap
            return back()->with('error', 'Gagal memuat data perhitungan: ' . $e->getMessage());
        }
    }

    /**
     * Menjalankan proses perhitungan dan menyimpan Hasil Akhir.
     */
    public function runCalculation($idKeputusan)
    {
        try {
            // Panggil Service untuk menjalankan dan menyimpan perhitungan
            $this->sawService->executeAndSaveResult($idKeputusan);

            return redirect()->route('admin.spk.manage.hasil', $idKeputusan)
                             ->with('success', 'Perhitungan SAW berhasil dieksekusi dan hasil disimpan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan perhitungan: ' . $e->getMessage());
        }
    }
}