<?php

namespace App\Services;

class AhpService
{
    /**
     * Membentuk matrix pairwise dari input.
     *
     * @param array $input Array ['cID1_ID2' => nilai, ...]
     * @param int $n Jumlah kriteria
     * @param array $kriteriaIdMapByIndex Array [0 => ID1, 1 => ID2, ...]
     */
    public function buildMatrix(array $input, int $n, array $kriteriaIdMapByIndex): array
    {
        $matrix = array_fill(0, $n, array_fill(0, $n, 1));

        $kriteriaIndexMap = array_flip($kriteriaIdMapByIndex);

        foreach ($input as $key => $value) {
            if (!$value || $value <= 0)
                continue;

            list($k1Id, $k2Id) = explode('_', str_replace('c', '', $key));
            $k1Id = (int) $k1Id;
            $k2Id = (int) $k2Id;

            $row = $kriteriaIndexMap[$k1Id] ?? null;
            $col = $kriteriaIndexMap[$k2Id] ?? null;

            if (is_null($row) || is_null($col)) {
                continue;
            }

            $matrix[$row][$col] = floatval($value);
            $matrix[$col][$row] = 1 / floatval($value);
        }

        return $matrix;
    }

    /**
     * Menghitung Eigen Vector, CR, dan semua matriks perantara.
     */
    public function calculateAhp(array $matrix, int $n): array
    {
        $results = [];

        $colSum = array_fill(0, $n, 0);
        for ($col = 0; $col < $n; $col++) {
            for ($row = 0; $row < $n; $row++) {
                $colSum[$col] += $matrix[$row][$col];
            }
        }
        $results['col_sum'] = $colSum;

        $normalizedMatrix = array_fill(0, $n, array_fill(0, $n, 0));
        for ($row = 0; $row < $n; $row++) {
            for ($col = 0; $col < $n; $col++) {
                $normalizedMatrix[$row][$col] = $matrix[$row][$col] / ($colSum[$col] ?: 1);
            }
        }
        $results['normalized_matrix'] = $normalizedMatrix;

        $eigenVector = array_fill(0, $n, 0);
        for ($row = 0; $row < $n; $row++) {
            $sumRow = 0;
            for ($col = 0; $col < $n; $col++) {
                $sumRow += $normalizedMatrix[$row][$col];
            }
            $eigenVector[$row] = $sumRow / $n;
        }
        $results['weights'] = $eigenVector;

        $weightedSum = array_fill(0, $n, 0);
        for ($row = 0; $row < $n; $row++) {
            for ($col = 0; $col < $n; $col++) {
                $weightedSum[$row] += $matrix[$row][$col] * $eigenVector[$col];
            }
        }
        $results['weighted_sum'] = $weightedSum;

        $ratioVector = array_fill(0, $n, 0);
        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            $ratioVector[$i] = $weightedSum[$i] / ($eigenVector[$i] ?: 1);
            $lambdaMax += $ratioVector[$i];
        }
        $lambdaMax /= $n;
        $results['ratio_vector'] = $ratioVector;

        $ci = ($lambdaMax - $n) / ($n - 1 ?: 1);

        $ri = [0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45];
        $riValue = $ri[$n - 1] ?? 1.45;

        $cr = ($riValue > 0) ? $ci / $riValue : 0;

        $results['crData'] = [
            'lambda_max' => $lambdaMax,
            'ci'         => $ci,
            'ri'         => $riValue,
            'cr'         => $cr,
        ];

        return $results;
    }
}