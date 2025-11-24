<?php

namespace App\Services;

class AhpService
{
    // ... (buildMatrix tetap sama) ...

    /**
     * Membentuk matrix pairwise dari input user
     */
    /**
     * Membentuk matrix pairwise dari input user
     * @param array $kriteriaIdMapByIndex Array yang berisi ID kriteria berdasarkan urutan index [0 => ID1, 1 => ID2, ...]
     */
    public function buildMatrix(array $input, int $n, array $kriteriaIdMapByIndex): array
    {
        $matrix = array_fill(0, $n, array_fill(0, $n, 1));

        // Buat peta balik: ID Kriteria => Index Matriks
        $kriteriaIndexMap = array_flip($kriteriaIdMapByIndex);

        // Asumsi $input adalah array ['cID1_ID2' => nilai, ...]
        foreach ($input as $key => $value) {
            if (!$value || $value <= 0)
                continue;

            // 1. Ambil ID Kriteria
            list($k1Id, $k2Id) = explode('_', str_replace('c', '', $key));
            $k1Id = (int) $k1Id;
            $k2Id = (int) $k2Id;

            // 2. Map ID Kriteria ke Index Matriks yang benar (0, 1, 2, ...)
            // Gunakan $kriteriaIndexMap untuk mendapatkan index yang benar
            $row = $kriteriaIndexMap[$k1Id] ?? null;
            $col = $kriteriaIndexMap[$k2Id] ?? null;

            // Cek jika ID kriteria yang dikirim oleh form TIDAK ADA di kriteria yang valid (Kriteria Hilang)
            if (is_null($row) || is_null($col)) {
                continue;
            }

            // 3. Masukkan nilai ke Matriks
            $matrix[$row][$col] = floatval($value);
            $matrix[$col][$row] = 1 / floatval($value);
        }

        return $matrix;
    }


    /**
     * Menghitung Eigen Vector, CR, dan menghasilkan semua matriks perantara.
     */
    public function calculateAhp(array $matrix, int $n): array
    {
        $results = [];

        // 1. Hitung Jumlah Kolom (Sum of Columns)
        $colSum = array_fill(0, $n, 0);
        for ($col = 0; $col < $n; $col++) {
            for ($row = 0; $row < $n; $row++) {
                $colSum[$col] += $matrix[$row][$col];
            }
        }
        $results['col_sum'] = $colSum;


        // 2. Normalisasi Matriks (Divided by Column Sum)
        $normalizedMatrix = array_fill(0, $n, array_fill(0, $n, 0));
        for ($row = 0; $row < $n; $row++) {
            for ($col = 0; $col < $n; $col++) {
                $normalizedMatrix[$row][$col] = $matrix[$row][$col] / ($colSum[$col] ?: 1);
            }
        }
        $results['normalized_matrix'] = $normalizedMatrix;


        // 3. Hitung Eigen Vector / Vektor Prioritas (Average of Row in Normalized Matrix)
        $eigenVector = array_fill(0, $n, 0);
        for ($row = 0; $row < $n; $row++) {
            $sumRow = 0;
            for ($col = 0; $col < $n; $col++) {
                $sumRow += $normalizedMatrix[$row][$col];
            }
            $eigenVector[$row] = $sumRow / $n;
        }
        $results['weights'] = $eigenVector;


        // 4. Hitung Lambda Max dan Konsistensi
        $weightedSum = array_fill(0, $n, 0);
        for ($row = 0; $row < $n; $row++) {
            for ($col = 0; $col < $n; $col++) {
                $weightedSum[$row] += $matrix[$row][$col] * $eigenVector[$col];
            }
        }
        $results['weighted_sum'] = $weightedSum;

        // 5. Hitung Ratio Vektor
        $ratioVector = array_fill(0, $n, 0);
        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            $ratioVector[$i] = $weightedSum[$i] / ($eigenVector[$i] ?: 1);
            $lambdaMax += $ratioVector[$i];
        }
        $lambdaMax /= $n;
        $results['ratio_vector'] = $ratioVector;


        // 6. Hitung CI dan CR
        $ci = ($lambdaMax - $n) / ($n - 1 ?: 1); // Consistency Index

        $ri = [0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45];
        $riValue = $ri[$n - 1] ?? 1.45;

        $cr = ($riValue > 0) ? $ci / $riValue : 0; // Consistency Ratio

        $results['crData'] = [
            'lambda_max' => $lambdaMax,
            'ci' => $ci,
            'ri' => $riValue, 
            'cr' => $cr,
        ];

        return $results;
    }
}