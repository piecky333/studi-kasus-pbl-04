<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\keputusan;
use App\Models\hasilakhir;
use App\Services\SawService; //mengambil layanan SawService
use App\Services\WeightService; // Untuk mendapatkan data bobot dan tipe

class SpkCalculationController extends Controller
{
    protected SawService $sawService;
    protected WeightService $weightService;

    public function __construct(SawService $sawService, WeightService $weightService)
    {
        $this->sawService = $sawService;
        $this->weightService = $weightService;
    }

    /**
     * Menampilkan detail Perhitungan (Data Perhitungan: Normalisasi & Pembobotan).
     */
    public function showPerhitungan($idKeputusan)
    {
        $keputusan = keputusan::findOrFail($idKeputusan);
        
        try {
            // Panggil metode publik baru di SawService yang mengurus semua langkah
            $perhitunganData = $this->sawService->calculateProcessData($idKeputusan);

            // Ambil Bobot & Tipe untuk header tabel dari WeightService
            $weights = $this->weightService->getSawWeights($idKeputusan);
            $criteriaType = $this->weightService->getCriteriaType($idKeputusan);

            return view('spk.perhitungan_view', [
                'keputusan' => $keputusan,
                'perhitunganData' => $perhitunganData,
                'weights' => $weights,
                'criteriaType' => $criteriaType,
                'pageTitle' => 'Data Proses Perhitungan SAW'
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data perhitungan: ' . $e->getMessage());
        }
    }

    /**
     * Menjalankan proses perhitungan SPK dan menyimpan Hasil Akhir (Data Hasil Akhir).
     */
    public function runCalculation($idKeputusan)
    {
        try {
            // Panggil Service untuk menjalankan dan menyimpan perhitungan
            $this->sawService->executeAndSaveResult($idKeputusan);

            return redirect()->route('spk.manage.hasil', $idKeputusan)
                             ->with('success', 'Perhitungan SAW berhasil dieksekusi dan hasil disimpan!');
        } catch (\Exception $e) {
            // Jika AHP tidak konsisten atau data tidak ditemukan
            return back()->with('error', 'Gagal melakukan perhitungan: ' . $e->getMessage());
        }
    }
}