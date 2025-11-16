<?php

namespace App\Services;

use App\Services\AhpService; 
use App\Models\Kriteria; // Tambahkan import Model Kriteria

/**
 * Layanan untuk mengelola dan menyediakan bobot (Wj) dan tipe kriteria
 * yang digunakan dalam perhitungan SAW, diambil dari database atau AHP dinamis.
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
     * Menggunakan hasil AHP yang dinamis (jika diimplementasikan) atau bobot tersimpan.
     * * Saat ini, kita asumsikan bobot AHP (bobot_kriteria) sudah disimpan
     * ke dalam tabel kriteria setelah perhitungan AHP yang dinamis.
     * * @param int $idKeputusan ID Keputusan yang sedang diproses.
     * @return array<string, float> [kode_kriteria => bobot]
     */
    public function getSawWeights(int $idKeputusan): array
    {
        // 1. Ambil data kriteria dari database
        $kriteriaList = Kriteria::where('id_keputusan', $idKeputusan)->get();

        if ($kriteriaList->isEmpty()) {
            throw new \Exception("Tidak ada kriteria yang ditemukan untuk Keputusan ID: " . $idKeputusan);
        }

        $weights = [];
        foreach ($kriteriaList as $kriteria) {
            // Mengambil bobot yang sudah dihitung (misalnya hasil AHP) yang disimpan di kolom 'bobot_kriteria'
            $weights[$kriteria->kode_kriteria] = (float) $kriteria->bobot_kriteria;
        }

        // Keterangan: Jika AHP belum diimplementasikan secara dinamis, 
        // pastikan kolom 'bobot_kriteria' diisi manual atau dengan fallback.
        // Jika Anda ingin bobot dihitung on-the-fly dari AHP di sini, 
        // Anda harus memberikan matriks perbandingan dari database ke AhpService.

        return $weights;
    }
    
    /**
     * Mengambil tipe kriteria (Benefit atau Cost) dari database.
     *
     * @param int $idKeputusan ID Keputusan yang sedang diproses.
     * @return array<string, string> [kode_kriteria => tipe ('benefit' atau 'cost')]
     */
    public function getCriteriaType(int $idKeputusan): array
    {
        // Ambil data kriteria dari database
        $kriteriaList = Kriteria::where('id_keputusan', $idKeputusan)->get();

        $criteriaType = [];
        foreach ($kriteriaList as $kriteria) {
            $criteriaType[$kriteria->kode_kriteria] = strtolower($kriteria->jenis_kriteria);
        }
        
        return $criteriaType;
    }
}