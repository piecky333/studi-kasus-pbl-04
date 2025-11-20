<?php

namespace App\Services;

use App\Services\SpkDataService; // Untuk mengambil data mentah
use App\Services\WeightService;  // Untuk mengambil bobot dan tipe kriteria

/**
 * Layanan yang bertanggung jawab untuk melakukan perhitungan metode Simple Additive Weighting (SAW).
 */
class SawService
{
    protected SpkDataService $dataService;
    protected WeightService $weightService;

    public function __construct(SpkDataService $dataService, WeightService $weightService)
    {
        $this->dataService = $dataService;
        $this->weightService = $weightService;
    }

    /**
     * Menjalankan seluruh proses perhitungan SAW (Normalisasi hingga Ranking).
     * * @param int $idKeputusan
     * @return array Data lengkap proses perhitungan.
     */
    public function calculateProcessData(int $idKeputusan): array
    {
        // 1. Ambil Data Mentah (Xij)
        $rawData = $this->dataService->getSpkRawData($idKeputusan);
        $alternatives = $rawData['alternatives'];
        $criteriaList = $rawData['criteria']; // Daftar Kriteria (id, kode, jenis)

        if (empty($alternatives) || $criteriaList->isEmpty()) {
            throw new \Exception("Data Kriteria atau Alternatif tidak lengkap untuk Keputusan ID: " . $idKeputusan);
        }

        // 2. Ambil Bobot (Wj) dan Tipe Kriteria
        $weights = $this->weightService->getSawWeights($idKeputusan); // [KodeKriteria => Bobot]
        $criteriaType = $this->weightService->getCriteriaType($idKeputusan); // [KodeKriteria => Jenis]
        $criteriaKeys = $criteriaList->pluck('kode_kriteria')->toArray();

        // 3. Normalisasi Matriks (Rij)
        $normalizationData = $this->normalizeMatrix($alternatives, $criteriaKeys, $criteriaType);

        // 4. Perhitungan Nilai Preferensi (Vi)
        $rankingData = $this->calculatePreferences($normalizationData['normalized_matrix'], $weights, $alternatives, $criteriaKeys);
        
        // 5. Sorting (Perangkingan)
        usort($rankingData, function ($a, $b) {
            return $b['final_score'] <=> $a['final_score']; // Sorting menurun (descending)
        });

        // 6. Tambahkan Ranking
        foreach ($rankingData as $index => &$item) {
            $item['rank'] = $index + 1;
        }

        return [
            'raw_data' => $alternatives,
            'criteria_metadata' => $criteriaList,
            'weights' => $weights,
            'criteria_type' => $criteriaType,
            'normalization_summary' => $normalizationData['summary'], // Max/Min per kriteria
            'normalized_matrix' => $normalizationData['normalized_matrix'], // Matriks Rij
            'ranking_results' => $rankingData, // Hasil Vi
        ];
    }

    /**
     * Melakukan proses normalisasi matriks Xij menjadi Rij.
     */
    protected function normalizeMatrix(array $alternatives, array $criteriaKeys, array $criteriaType): array
    {
        $summary = [];
        $normalizedMatrix = [];

        // 3a. Cari Nilai Max/Min per Kriteria
        foreach ($criteriaKeys as $kodeKriteria) {
            $values = array_column($alternatives, $kodeKriteria);
            $summary[$kodeKriteria] = [
                'max' => max($values),
                'min' => min($values),
            ];
        }

        // 3b. Hitung Rij (Normalisasi)
        foreach ($alternatives as $altIndex => $alt) {
            $normalizedMatrix[$altIndex] = $alt;
            
            foreach ($criteriaKeys as $kodeKriteria) {
                $max = $summary[$kodeKriteria]['max'];
                $min = $summary[$kodeKriteria]['min'];
                $xij = $alt[$kodeKriteria];
                $type = $criteriaType[$kodeKriteria];

                $rij = 0;
                
                // Rumus Normalisasi SAW
                if ($type === 'benefit') {
                    // Benefit: Rij = Xij / Max(Xij)
                    $rij = ($max != 0) ? $xij / $max : 0;
                } elseif ($type === 'cost') {
                    // Cost: Rij = Min(Xij) / Xij
                    $rij = ($xij != 0) ? $min / $xij : 0;
                }

                $normalizedMatrix[$altIndex][$kodeKriteria] = $rij;
            }
        }

        return [
            'summary' => $summary,
            'normalized_matrix' => $normalizedMatrix,
        ];
    }

    /**
     * Menghitung nilai preferensi akhir (Vi) untuk setiap alternatif.
     */
    protected function calculatePreferences(array $normalizedMatrix, array $weights, array $alternatives, array $criteriaKeys): array
    {
        $rankingData = [];

        foreach ($normalizedMatrix as $altIndex => $altRij) {
            $finalScore = 0;

            // Vi = SUM (Wj * Rij)
            foreach ($criteriaKeys as $kodeKriteria) {
                $rij = $altRij[$kodeKriteria];
                $wj = $weights[$kodeKriteria];
                
                $finalScore += $rij * $wj;
            }

            $rankingData[] = [
                'id_alternatif' => $alternatives[$altIndex]['id_alternatif'],
                'nama' => $alternatives[$altIndex]['nama'],
                'final_score' => $finalScore,
                // Matriks Ternormalisasi (Rij) dimasukkan untuk kemudahan display
                'rij_data' => $altRij, 
            ];
        }

        return $rankingData;
    }
    
    /**
     * Menyimpan hasil ranking dan skor akhir ke database.
     * Metode ini akan dipanggil oleh SpkCalculationController@runCalculation.
     */
    public function executeAndSaveResult(int $idKeputusan): void
    {
        $results = $this->calculateProcessData($idKeputusan);
        
        // Asumsi: Anda memiliki Model HasilAkhir yang siap di-insert
        // Misalnya: use App\Models\HasilAkhir; 
        
        foreach ($results['ranking_results'] as $result) {
            \App\Models\HasilAkhir::updateOrCreate(
                [
                    'id_keputusan' => $idKeputusan,
                    'id_alternatif' => $result['id_alternatif'],
                ],
                [
                    'nilai_preferensi' => $result['final_score'],
                    'ranking' => $result['rank'],
                    // Anda mungkin juga menyimpan bobot yang digunakan saat perhitungan
                    // 'bobot_digunakan' => json_encode($results['weights']),
                ]
            );
        }
    }
}