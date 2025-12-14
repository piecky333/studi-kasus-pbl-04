<?php

namespace App\Services;

use App\Models\admin\Prestasi;

class SpkCalculator
{
    /**
     * Bobot Tingkat Prestasi
     */
    protected static $levelWeights = [
        'Internasional' => 5,
        'Nasional' => 4,
        'Provinsi' => 3,
        'Kabupaten/Kota' => 2,
        'Internal' => 1,
    ];

    /**
     * Bobot Juara (Rank)
     * Asumsi standar jika tidak spesifik di gambar
     */
    protected static $rankWeights = [
        'Juara 1' => 5,
        'Emas' => 5,
        'Juara 2' => 4,
        'Perak' => 4,
        'Juara 3' => 3,
        'Perunggu' => 3,
        'Harapan 1' => 2,
        'Harapan 2' => 1, // Atau 1.5?
        'Finalis' => 1,
    ];

    public static function calculateTingkatScore($prestasiList)
    {
        $score = 0;
        foreach ($prestasiList as $p) {
            $level = $p->tingkat_prestasi;
            $score += self::$levelWeights[$level] ?? 1; // Default 1
        }
        return $score;
    }

    public static function calculateJuaraScore($prestasiList)
    {
        $score = 0;
        foreach ($prestasiList as $p) {
            $rank = $p->juara;
            // Simple string matching
            foreach (self::$rankWeights as $key => $val) {
                if (stripos($rank, $key) !== false) {
                    $score += $val;
                    break;
                }
            }
        }
        return $score;
    }
}
