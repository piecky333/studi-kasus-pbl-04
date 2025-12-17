<?php

namespace App\Services;

use App\Models\admin\Prestasi;
use App\Models\SubKriteria;
use App\Models\kriteria;

class SpkCalculator
{
    /**
     * Menghitung skor Tingkat Prestasi berdasarkan Sub Kriteria di database.
     * Mencocokkan 'tingkat_prestasi' dari data Prestasi dengan 'nama_subkriteria'.
     *
     * @param \Illuminate\Support\Collection $prestasiList
     * @param Kriteria $kriteriaObj
     * @return float|int
     */
    public static function calculateTingkatScore($prestasiList, $kriteriaObj)
    {
        // Ambil semua sub kriteria untuk kriteria ini
        // Asumsi: Sub Kriteria 'Nasional', 'Provinsi', dll sudah diinput di DB
        $subKriteriaList = SubKriteria::where('id_kriteria', $kriteriaObj->id_kriteria)->get();

        $score = 0;
        foreach ($prestasiList as $p) {
            $level = $p->tingkat_prestasi; // e.g., "Nasional"
            
            // Cari Sub Kriteria yang namaya COCOK dengan level prestasi
            // Pencarian Case-Insensitive
            $match = $subKriteriaList->first(function ($sub) use ($level) {
                return strcasecmp($sub->nama_subkriteria, $level) === 0;
            });

            if ($match) {
                $score += $match->nilai;
            } else {
                // Opsional: Log jika tidak ada match, atau beri nilai default 1
                $score += 1; 
            }
        }
        return $score;
    }

    /**
     * Menghitung skor Juara berdasarkan Sub Kriteria di database.
     * Mencocokkan 'juara' dari data Prestasi dengan keyword di 'nama_subkriteria'.
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
            $rankString = $p->juara; // e.g., "Juara 1", "Gold Medal", "Harapan 1"
            
            // Cari sub kriteria yang keyword-nya muncul di string ranking mahasiswa
            // Contoh: Sub Kriteria "Juara 1" cocok dengan data "Juara 1 Lomba Coding"
            // Prioritaskan match yang paling spesifik/panjang jika perlu, 
            // tapi untuk sekarang kita ambil first match yang nilai paling tinggi (asumsi behavior greedy)
            
            // Sort sub kriteria by nilai descending agar "Juara 1" (5) dicek sebelum "Juara" (general) jika ada
            $sortedSubs = $subKriteriaList->sortByDesc('nilai');
            
            $foundMatch = false;
            foreach ($sortedSubs as $sub) {
                // Cek apakah nama sub kriteria ada di dalam string input user
                // Gunakan stripos untuk case-insensitive contains
                if (stripos($rankString, $sub->nama_subkriteria) !== false) {
                    $score += $sub->nilai;
                    $foundMatch = true;
                    break; // Ambil satu nilai tertinggi yang cocok per prestasi
                }
            }
            
            if (!$foundMatch) {
                $score += 1; // Default jika tidak ada keyword yang cocok
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
            $jenis = $p->jenis_prestasi; // e.g., "Akademik", "Non-Akademik"
            
            $match = $subKriteriaList->first(function ($sub) use ($jenis) {
                return strcasecmp($sub->nama_subkriteria, $jenis) === 0;
            });

            if ($match) {
                $score += $match->nilai;
            } else {
                $score += 1; // Default
            }
        }
        return $score;
    }
}
