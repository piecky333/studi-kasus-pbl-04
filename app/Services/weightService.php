<?php

namespace App\Services;

class WeightService
{
    protected \App\Services\AhpService $ahpService;

    public function __construct(\App\Services\AhpService $ahpService)
    {
        $this->ahpService = $ahpService;
    }

    public function getSawWeights(int $idKeputusan): array
    {
        $kriteriaList = \App\Models\Kriteria::where('id_keputusan', $idKeputusan)->get();

        if ($kriteriaList->isEmpty()) {
            throw new \Exception("Tidak ada kriteria yang ditemukan untuk Keputusan ID: " . $idKeputusan);
        }

        $weights = [];
        foreach ($kriteriaList as $kriteria) {
            $weights[$kriteria->kode_kriteria] = (float) $kriteria->bobot_kriteria;
        }

        return $weights;
    }

    public function getCriteriaType(int $idKeputusan): array
    {
        $kriteriaList = \App\Models\Kriteria::where('id_keputusan', $idKeputusan)->get();

        $criteriaType = [];
        foreach ($kriteriaList as $kriteria) {
            $criteriaType[$kriteria->kode_kriteria] = strtolower($kriteria->jenis_kriteria);
        }

        return $criteriaType;
    }
}
