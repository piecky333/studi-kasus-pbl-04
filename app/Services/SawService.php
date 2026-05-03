<?php

namespace App\Services;

use App\Services\SpkDataService;
use App\Services\WeightService;

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
     *
     * @param int $idKeputusan
     * @return array Data lengkap proses perhitungan.
     */
    public function calculateProcessData(int $idKeputusan): array
    {
        $rawData = $this->dataService->getSpkRawData($idKeputusan);
        $alternatives = $rawData['alternatives'];
        $criteriaList = $rawData['criteria'];

        if (empty($alternatives) || $criteriaList->isEmpty()) {
            throw new \Exception("Data Kriteria atau Alternatif tidak lengkap untuk Keputusan ID: " . $idKeputusan);
        }

        $weights = $this->weightService->getSawWeights($idKeputusan);
        $criteriaType = $this->weightService->getCriteriaType($idKeputusan);
        $criteriaKeys = $criteriaList->pluck('kode_kriteria')->toArray();

        $normalizationData = $this->normalizeMatrix($alternatives, $criteriaKeys, $criteriaType);

        $rankingData = $this->calculatePreferences($normalizationData['normalized_matrix'], $weights, $alternatives, $criteriaKeys);

        usort($rankingData, function ($a, $b) {
            return $b['final_score'] <=> $a['final_score'];
        });

        foreach ($rankingData as $index => &$item) {
            $item['rank'] = $index + 1;
        }

        return [
            'raw_data'             => $alternatives,
            'criteria_metadata'    => $criteriaList,
            'weights'              => $weights,
            'criteria_type'        => $criteriaType,
            'normalization_summary' => $normalizationData['summary'],
            'normalized_matrix'    => $normalizationData['normalized_matrix'],
            'ranking_results'      => $rankingData,
        ];
    }

    /**
     * Melakukan proses normalisasi matriks Xij menjadi Rij.
     */
    protected function normalizeMatrix(array $alternatives, array $criteriaKeys, array $criteriaType): array
    {
        $summary = [];
        $normalizedMatrix = [];

        foreach ($criteriaKeys as $kodeKriteria) {
            $values = array_column($alternatives, $kodeKriteria);
            $summary[$kodeKriteria] = [
                'max' => max($values),
                'min' => min($values),
            ];
        }

        foreach ($alternatives as $altIndex => $alt) {
            $normalizedMatrix[$altIndex] = $alt;

            foreach ($criteriaKeys as $kodeKriteria) {
                $max  = $summary[$kodeKriteria]['max'];
                $min  = $summary[$kodeKriteria]['min'];
                $xij  = $alt[$kodeKriteria];
                $type = $criteriaType[$kodeKriteria];

                $rij = 0;

                if ($type === 'benefit') {
                    $rij = ($max != 0) ? $xij / $max : 0;
                } elseif ($type === 'cost') {
                    $rij = ($xij != 0) ? $min / $xij : 0;
                }

                $normalizedMatrix[$altIndex][$kodeKriteria] = $rij;
            }
        }

        return [
            'summary'          => $summary,
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

            foreach ($criteriaKeys as $kodeKriteria) {
                $rij = $altRij[$kodeKriteria];
                $wj  = $weights[$kodeKriteria];
                $finalScore += $rij * $wj;
            }

            $rankingData[] = [
                'id_alternatif' => $alternatives[$altIndex]['id_alternatif'],
                'nama'          => $alternatives[$altIndex]['nama'],
                'final_score'   => $finalScore,
                'rij_data'      => $altRij,
            ];
        }

        return $rankingData;
    }

    /**
     * Menyimpan hasil ranking dan skor akhir ke database.
     */
    public function executeAndSaveResult(int $idKeputusan): void
    {
        $results = $this->calculateProcessData($idKeputusan);

        foreach ($results['ranking_results'] as $result) {
            \App\Models\HasilAkhir::updateOrCreate(
                [
                    'id_alternatif' => $result['id_alternatif'],
                ],
                [
                    'skor_akhir' => $result['final_score'],
                    'rangking'   => $result['rank'],
                ]
            );
        }
    }
}