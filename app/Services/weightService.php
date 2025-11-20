<?php

namespace App\Services;

use App\Services\AhpService; 
use App\Models\Kriteria; 

/**
 * Layanan untuk mengelola dan menyediakan bobot (Wj) dan tipe kriteria
 * yang digunakan dalam perhitungan SAW.
 */
class WeightService
{
    protected AhpService $ahpService;

    public function __construct(AhpService $ahpService)
    {
        $this->ahpService = $ahpService;
    }

    /**
     * Mengambil bobot kriteria yang akan digunakan dalam perhitungan SAW.
     */
    public function getSawWeights(int $idKeputusan): array
    {
        $kriteriaList = Kriteria::where('id_keputusan', $idKeputusan)->get();

        if ($kriteriaList->isEmpty()) {
            throw new \Exception("Tidak ada kriteria yang ditemukan untuk Keputusan ID: " . $idKeputusan);
        }

        $weights = [];
        foreach ($kriteriaList as $kriteria) {
            $weights[$kriteria->kode_kriteria] = (float) $kriteria->bobot_kriteria;
        }

        return $weights;
    }
    
    /**
     * Mengambil tipe kriteria (Benefit atau Cost) dari database.
     */
    public function getCriteriaType(int $idKeputusan): array
    {
        $kriteriaList = Kriteria::where('id_keputusan', $idKeputusan)->get();

        $criteriaType = [];
        foreach ($kriteriaList as $kriteria) {
            $criteriaType[$kriteria->kode_kriteria] = strtolower($kriteria->jenis_kriteria);
        }
        
        return $criteriaType;
    }
}