<?php

namespace App\Services;

use App\Models\hasilakhir; 
use App\Models\alternatif; 
use App\Services\SpkDataService; 
use Illuminate\Support\Facades\DB; 
use App\Services\WeightService; // Pastikan import ini ada

/**
 * Layanan untuk menghitung perangkingan menggunakan metode Simple Additive Weighting (SAW).
 */
class SawService
{
    protected WeightService $weightService;
    protected SpkDataService $spkDataService;

    public function __construct(WeightService $weightService, SpkDataService $spkDataService)
    {
        $this->weightService = $weightService;
        $this->spkDataService = $spkDataService;
    }

    /**
     * Metode publik baru untuk menampilkan data proses perhitungan (tanpa menyimpan).
     * Dipanggil oleh SpkCalculationController::showPerhitungan.
     *
     * @param int $idKeputusan
     * @return array Data perhitungan yang sudah di-rank.
     */
    public function calculateProcessData(int $idKeputusan): array
    {
        // 0. Ambil Data Mentah
        $dataResult = $this->spkDataService->getSpkRawData($idKeputusan);
        $rawData = $dataResult['alternatives'];

        if (empty($rawData)) {
            throw new \Exception("Tidak ada data alternatif atau penilaian yang ditemukan untuk perhitungan.");
        }

        // 1. Normalisasi Data (memanggil protected method)
        $normalizedData = $this->normalizeData($rawData, $idKeputusan);

        // 2. Hitung Nilai Preferensi dan Peringkat (memanggil protected method)
        $rankedData = $this->rankData($normalizedData, $idKeputusan);
        
        return $rankedData;
    }

    /**
     * Metode utama untuk mengeksekusi perhitungan dan menyimpan hasil.
     * (executeAndSaveResult tetap sama)
     */
    public function executeAndSaveResult(int $idKeputusan): array
    {
        // ... (Logika executeAndSaveResult tetap sama)
        $dataResult = $this->spkDataService->getSpkRawData($idKeputusan);
        $rawData = $dataResult['alternatives'];

        if (empty($rawData)) {
            throw new \Exception("Tidak ada data alternatif atau penilaian yang ditemukan.");
        }

        $normalizedData = $this->normalizeData($rawData, $idKeputusan);
        $rankedData = $this->rankData($normalizedData, $idKeputusan);

        $this->saveResults($idKeputusan, $rankedData);

        return $rankedData;
    }
    
    // ... (protected function normalizeData tetap sama)
    protected function normalizeData(array $rawData, int $idKeputusan): array
    {
        // ... (Logika Normalisasi Anda) ...
        $weights = $this->weightService->getSawWeights($idKeputusan);
        $criteriaType = $this->weightService->getCriteriaType($idKeputusan);
        
        $criteriaKeys = array_keys($weights);
        $minMaxValues = [];
        $normalizedData = [];

        foreach ($criteriaKeys as $key) {
            $values = array_column($rawData, $key);
            $minMaxValues[$key]['max'] = max($values) ?: 1e-9;
            $minMaxValues[$key]['min'] = min($values) ?: 1e-9;
        }

        foreach ($rawData as $alternatif) {
            $normalizedAlternatif = [
                'id_alternatif' => $alternatif['id_alternatif'], 
                'nama' => $alternatif['nama'],
                'normalized' => [], 
            ];

            foreach ($criteriaKeys as $key) {
                $value = $alternatif[$key];
                $max = $minMaxValues[$key]['max'];
                $min = $minMaxValues[$key]['min'];
                $type = $criteriaType[$key]; 

                $rij = 0; 
                if ($type === 'benefit') {
                    $rij = $value / $max;
                } elseif ($type === 'cost') {
                    $rij = $min / ($value ?: 1e-9);
                }

                $normalizedAlternatif['normalized'][$key] = $rij;
            }
            $normalizedData[] = $normalizedAlternatif;
        }

        return $normalizedData;
    }
    
    // ... (protected function rankData tetap sama)
    protected function rankData(array $normalizedData, int $idKeputusan): array
    {
        // ... (Logika Ranking Anda) ...
        $weights = $this->weightService->getSawWeights($idKeputusan);
        $criteriaKeys = array_keys($weights);
        $rankedData = [];

        foreach ($normalizedData as $item) {
            $preferenceValue = 0;
            
            foreach ($criteriaKeys as $key) {
                $weight = $weights[$key]; 
                $normalizedScore = $item['normalized'][$key];
                $preferenceValue += ($weight * $normalizedScore);
            }

            $rankedData[] = [
                'id_alternatif' => $item['id_alternatif'], 
                'nama' => $item['nama'],
                'nilai_preferensi_V' => round($preferenceValue, 4), 
                'normalized_scores' => $item['normalized'],
            ];
        }

        usort($rankedData, function ($a, $b) {
            return $b['nilai_preferensi_V'] <=> $a['nilai_preferensi_V'];
        });

        $rank = 1;
        $prevValue = null;
        foreach ($rankedData as $index => &$item) {
            if ($prevValue !== null && $item['nilai_preferensi_V'] < $prevValue) {
                $rank = $index + 1;
            }
            $item['peringkat'] = $rank;
            $prevValue = $item['nilai_preferensi_V'];
        }

        return $rankedData;
    }

    // ... (protected function saveResults tetap sama)
    protected function saveResults(int $idKeputusan, array $rankedData): void
    {
        DB::transaction(function () use ($idKeputusan, $rankedData) {
            $alternatifIds = alternatif::where('id_keputusan', $idKeputusan)->pluck('id_alternatif');
            hasilakhir::whereIn('id_alternatif', $alternatifIds)->delete();

            $insertData = [];
            foreach ($rankedData as $data) {
                $insertData[] = [
                    'id_alternatif' => $data['id_alternatif'],
                    'skor_akhir' => $data['nilai_preferensi_V'],
                    'rangking' => $data['peringkat'],
                ];
            }
            hasilakhir::insert($insertData);
        });
    }
}