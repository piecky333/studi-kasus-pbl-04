<?php

namespace App\Services;

use App\Models\hasilakhir; 
use App\Models\alternatif; // Tambahkan import Model Alternatif
use App\Services\SpkDataService; // Tambahkan import SpkDataService
use Illuminate\Support\Facades\DB; // Untuk transaksi database

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
     * Metode utama untuk mengeksekusi perhitungan dan menyimpan hasil.
     *
     * @param int $idKeputusan
     * @return array Data yang sudah diurutkan (ranked)
     */
    public function executeAndSaveResult(int $idKeputusan): array
    {
        // 0. Ambil Data Mentah
        $dataResult = $this->spkDataService->getSpkRawData($idKeputusan);
        $rawData = $dataResult['alternatives'];

        if (empty($rawData)) {
            throw new \Exception("Tidak ada data alternatif atau penilaian yang ditemukan.");
        }

        // 1. Normalisasi Data
        $normalizedData = $this->normalizeData($rawData, $idKeputusan);

        // 2. Hitung Nilai Preferensi dan Peringkat
        $rankedData = $this->rankData($normalizedData, $idKeputusan);

        // 3. Simpan Hasil Akhir ke Database
        $this->saveResults($idKeputusan, $rankedData);

        return $rankedData;
    }

    /**
     * Langkah 1: Normalisasi Matriks Keputusan (Rij).
     *
     * @param array $rawData
     * @param int $idKeputusan
     * @return array
     */
    protected function normalizeData(array $rawData, int $idKeputusan): array
    {
        // Ambil bobot dan tipe kriteria secara dinamis berdasarkan $idKeputusan
        $weights = $this->weightService->getSawWeights($idKeputusan);
        $criteriaType = $this->weightService->getCriteriaType($idKeputusan);
        
        // ... (Logika normalisasi tetap sama seperti kode Anda sebelumnya)
        // Pastikan Anda mengubah 'id_mahasiswa' menjadi 'id_alternatif'
        
        $criteriaKeys = array_keys($weights);
        $minMaxValues = [];
        $normalizedData = [];

        // 1. Cari nilai Min/Max untuk setiap kriteria
        foreach ($criteriaKeys as $key) {
            $values = array_column($rawData, $key);
            $minMaxValues[$key]['max'] = max($values) ?: 1e-9;
            $minMaxValues[$key]['min'] = min($values) ?: 1e-9;
        }

        // 2. Lakukan Normalisasi
        foreach ($rawData as $alternatif) {
            $normalizedAlternatif = [
                'id_alternatif' => $alternatif['id_alternatif'], // Perubahan
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

    /**
     * Langkah 2: Hitung Nilai Preferensi Akhir (Vi) dan Lakukan Perangkingan.
     *
     * @param array $normalizedData
     * @param int $idKeputusan
     * @return array
     */
    protected function rankData(array $normalizedData, int $idKeputusan): array
    {
        // Ambil bobot secara dinamis berdasarkan $idKeputusan
        $weights = $this->weightService->getSawWeights($idKeputusan);
        $criteriaKeys = array_keys($weights);
        $rankedData = [];

        // 1. Hitung Nilai Preferensi (Vi)
        foreach ($normalizedData as $item) {
            $preferenceValue = 0;
            
            foreach ($criteriaKeys as $key) {
                $weight = $weights[$key]; 
                $normalizedScore = $item['normalized'][$key];
                $preferenceValue += ($weight * $normalizedScore);
            }

            $rankedData[] = [
                'id_alternatif' => $item['id_alternatif'], // Perubahan
                'nama' => $item['nama'],
                'nilai_preferensi_V' => round($preferenceValue, 4), 
                'normalized_scores' => $item['normalized'],
            ];
        }

        // 2 & 3. Urutkan dan Tambahkan Peringkat (Logika sama)
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

    /**
     * Langkah 3: Menyimpan hasil perhitungan ke tabel hasil_akhir.
     *
     * @param int $idKeputusan
     * @param array $rankedData
     */
    protected function saveResults(int $idKeputusan, array $rankedData): void
    {
        DB::transaction(function () use ($idKeputusan, $rankedData) {
            // Hapus hasil lama untuk keputusan ini
            $alternatifIds = alternatif::where('id_keputusan', $idKeputusan)->pluck('id_alternatif');
            hasilakhir::whereIn('id_alternatif', $alternatifIds)->delete();

            // Simpan hasil baru
            $insertData = [];
            foreach ($rankedData as $data) {
                $insertData[] = [
                    'id_alternatif' => $data['id_alternatif'],
                    'skor_akhir' => $data['nilai_preferensi_V'],
                    'rangking' => $data['peringkat'],
                    // Anda mungkin perlu menambahkan 'id_keputusan' di tabel hasil_akhir
                    // jika tidak semua alternatif ada di setiap keputusan.
                ];
            }
            
            // Menggunakan upsert atau insert
            hasilakhir::insert($insertData);
        });
    }
}