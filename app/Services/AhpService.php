<?php

namespace App\Services;

/**
 * Layanan untuk menghitung bobot kriteria menggunakan metode Analytic Hierarchy Process (AHP).
 * Hasil bobot (eigenvector) ini nantinya dapat digunakan oleh SAW.
 */
class AhpService
{
    // Indeks Acak (Random Index - RI) untuk matriks berukuran n x n
    // Digunakan untuk menghitung Consistency Ratio (CR)
    protected const RANDOM_INDEX = [
        1 => 0.00, 2 => 0.00, 3 => 0.58, 4 => 0.90, 5 => 1.12,
        6 => 1.24, 7 => 1.32, 8 => 1.41, 9 => 1.45, 10 => 1.49,
    ];

    /**
     * Matriks Perbandingan Berpasangan (Pairwise Comparison Matrix - PCM).
     * Nilai 1-9 berdasarkan skala Saaty.
     * Contoh ini menunjukkan: C1 dianggap 3x lebih penting dari C2, 5x dari C3.
     */
    protected array $comparisonMatrix = [
        // C1_IPK        C2_SkorPrestasi C3_JumlahPrestasi
        'C1_IPK'               => ['C1_IPK' => 1, 'C2_TotalSkorPrestasi' => 3,     'C3_JumlahPrestasi' => 5],
        'C2_TotalSkorPrestasi' => ['C1_IPK' => 1/3, 'C2_TotalSkorPrestasi' => 1,     'C3_JumlahPrestasi' => 3],
        'C3_JumlahPrestasi'    => ['C1_IPK' => 1/5, 'C2_TotalSkorPrestasi' => 1/3,   'C3_JumlahPrestasi' => 1],
    ];

    /**
     * Metode utama untuk menghitung bobot akhir dan rasio konsistensi.
     * * @return array{'weights': array<string, float>, 'cr': float, 'isConsistent': bool} 
     * Bobot kriteria, Rasio Konsistensi, dan Status Konsistensi.
     * @throws \Exception Jika Rasio Konsistensi tidak memenuhi syarat (CR > 0.1)
     */
    public function calculateWeights(): array
    {
        $matrix = $this->comparisonMatrix;
        $criteria = array_keys($matrix);
        $n = count($criteria);

        // 1. Hitung Matriks Ternormalisasi
        $normalizedMatrix = $this->normalizeMatrix($matrix);

        // 2. Hitung Vektor Bobot (Eigenvector)
        $weights = $this->calculateWeightsVector($normalizedMatrix);

        // 3. Cek Konsistensi
        $consistencyRatio = $this->calculateConsistencyRatio($weights, $matrix, $n);

        // Kriteria: CR harus kurang dari 0.10 (10%) untuk dianggap konsisten
        $isConsistent = $consistencyRatio <= 0.10;

        if (!$isConsistent) {
            // Jika CR > 0.10, Matriks tidak konsisten.
            // PENTING: Lemparkan Exception untuk menghentikan proses perhitungan SAW 
            // jika matriks sumber (AHP) tidak valid.
            throw new \Exception("Rasio Konsistensi (CR) Matriks AHP terlalu tinggi: " . round($consistencyRatio, 4) . ". Matriks perbandingan tidak konsisten dan harus direvisi.");
        } 
        
        // Pilihan: Jika konsisten, kita bisa memberikan log atau pesan konfirmasi.
        // echo "Matriks perbandingan KONSISTEN. CR: " . round($consistencyRatio, 4) . " (Di bawah 0.10)";
        
        return [
            'weights' => $weights,
            'cr' => round($consistencyRatio, 4),
            'isConsistent' => $isConsistent,
        ];
    }

    /**
     * Langkah 1: Normalisasi Matriks dengan membagi setiap elemen dengan jumlah kolomnya.
     */
    protected function normalizeMatrix(array $matrix): array
    {
        $criteria = array_keys($matrix);
        $columnSums = [];
        $normalizedMatrix = [];

        // Hitung Jumlah Kolom (Sum per Column)
        foreach ($criteria as $colKey) {
            $sum = 0;
            foreach ($criteria as $rowKey) {
                $sum += $matrix[$rowKey][$colKey];
            }
            $columnSums[$colKey] = $sum;
        }

        // Normalisasi Matriks
        foreach ($criteria as $rowKey) {
            foreach ($criteria as $colKey) {
                // Normalisasi = Nilai Matriks / Jumlah Kolom
                $normalizedMatrix[$rowKey][$colKey] = $matrix[$rowKey][$colKey] / $columnSums[$colKey];
            }
        }

        return $normalizedMatrix;
    }

    /**
     * Langkah 2: Menghitung Vektor Bobot dengan merata-ratakan (sum/n) baris yang ternormalisasi.
     */
    protected function calculateWeightsVector(array $normalizedMatrix): array
    {
        $criteria = array_keys($normalizedMatrix);
        $n = count($criteria);
        $weights = [];

        foreach ($criteria as $rowKey) {
            $sumRow = array_sum($normalizedMatrix[$rowKey]);
            // Bobot = Jumlah Baris / Jumlah Kriteria (n)
            $weights[$rowKey] = $sumRow / $n;
        }

        return $weights;
    }

    /**
     * Langkah 3: Menghitung Rasio Konsistensi (Consistency Ratio - CR).
     */
    protected function calculateConsistencyRatio(array $weights, array $matrix, int $n): float
    {
        if ($n <= 2) {
            return 0.0; // Matriks 1x1 atau 2x2 selalu konsisten
        }

        // 3a. Hitung Lambda Max (λmax)
        $lambdaMax = 0;
        $criteria = array_keys($matrix);

        foreach ($criteria as $colKey) {
            $sumProduct = 0;
            // Hitung (kolom matriks perbandingan * bobot)
            foreach ($criteria as $rowKey) {
                $sumProduct += $matrix[$rowKey][$colKey] * $weights[$rowKey];
            }
            // Tambahkan (sumProduct / bobot) ke Lambda Max
            $lambdaMax += ($sumProduct / ($weights[$colKey] ?: 1e-9)); // Hindari pembagian nol
        }

        // Rata-rata λmax
        $lambdaMax /= $n;

        // 3b. Hitung Consistency Index (CI)
        // CI = (λmax - n) / (n - 1)
        $consistencyIndex = ($lambdaMax - $n) / ($n - 1);

        // 3c. Hitung Consistency Ratio (CR)
        // CR = CI / RI (Random Index)
        $randomIndex = self::RANDOM_INDEX[$n] ?? 99.0; // Default nilai besar jika n tidak terdaftar

        if ($randomIndex === 0.0) {
            return 0.0;
        }

        $consistencyRatio = $consistencyIndex / $randomIndex;

        return $consistencyRatio;
    }
}