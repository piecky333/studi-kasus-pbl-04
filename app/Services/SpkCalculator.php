<?php

namespace App\Services;

use App\Models\Prestasi;
use App\Models\SubKriteria;
use App\Models\Kriteria;

class SpkCalculator
{
    /**
     * Menghitung skor Tingkat Prestasi berdasarkan Sub Kriteria di database.
     *
     * @param \Illuminate\Support\Collection $prestasiList
     * @param Kriteria $kriteriaObj
     * @return float|int
     */
    public static function calculateTingkatScore($prestasiList, $kriteriaObj)
    {
        $subKriteriaList = SubKriteria::where('id_kriteria', $kriteriaObj->id_kriteria)->get();

        $score = 0;
        foreach ($prestasiList as $p) {
            $level = $p->tingkat_prestasi;

            $match = $subKriteriaList->first(function ($sub) use ($level) {
                return strcasecmp($sub->nama_subkriteria, $level) === 0;
            });

            if ($match) {
                $score += $match->nilai;
            } else {
                $score += 1;
            }
        }
        return $score;
    }

    /**
     * Menghitung skor Juara berdasarkan Sub Kriteria di database.
     *
     * @param \Illuminate\Support\Collection $prestasiList
     * @param Kriteria $kriteriaObj
     * @return float|int
     */
    public static function calculateJuaraScore($prestasiList, $kriteriaObj)
    {
        $subKriteriaList = SubKriteria::where('id_kriteria', $kriteriaObj->id_kriteria)->get();

        $score = 0;
        foreach ($prestasiList as $p) {
            $rankString  = $p->juara;
            $sortedSubs  = $subKriteriaList->sortByDesc('nilai');
            $foundMatch  = false;

            foreach ($sortedSubs as $sub) {
                if (stripos($rankString, $sub->nama_subkriteria) !== false) {
                    $score += $sub->nilai;
                    $foundMatch = true;
                    break;
                }
            }

            if (!$foundMatch) {
                $score += 1;
            }
        }
        return $score;
    }

    /**
     * Menghitung skor Jenis Prestasi berdasarkan Sub Kriteria.
     *
     * @param \Illuminate\Support\Collection $prestasiList
     * @param Kriteria $kriteriaObj
     * @return float|int
     */
    public static function calculateJenisScore($prestasiList, $kriteriaObj)
    {
        $subKriteriaList = SubKriteria::where('id_kriteria', $kriteriaObj->id_kriteria)->get();

        $score = 0;
        foreach ($prestasiList as $p) {
            $jenis = $p->jenis_prestasi;

            $match = $subKriteriaList->first(function ($sub) use ($jenis) {
                return strcasecmp($sub->nama_subkriteria, $jenis) === 0;
            });

            if ($match) {
                $score += $match->nilai;
            } else {
                $score += 1;
            }
        }
        return $score;
    }
}
