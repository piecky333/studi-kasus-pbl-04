<?php

namespace App\Services;

use App\Models\spkkeputusan; 
use App\Models\alternatif; 
use App\Models\kriteria; 
use App\Models\penilaian; 

/**
 * Layanan untuk mengumpulkan data mentah (matriks keputusan Xij)
 * dari database berdasarkan Keputusan SPK yang spesifik.
 */
class SpkDataService
{
    // ... (tingkatScores dan peringkatScores, biarkan) ...

    /**
     * Mengambil data mentah SPK untuk proses perhitungan.
     * HARUS menerima $idKeputusan.
     *
     * @param int $idKeputusan ID dari SpkKeputusan yang akan diproses.
     * @return array Data yang siap untuk dinormalisasi (SAW).
     */
    public function getSpkRawData(int $idKeputusan): array
    {
        // 1. Ambil Kriteria yang terkait dengan keputusan ini
        $kriteriaList = kriteria::where('id_keputusan', $idKeputusan)
            ->get(['id_kriteria', 'kode_kriteria', 'jenis_kriteria', 'nama_kriteria', 'sumber_data']);
        
        $kriteriaMap = $kriteriaList->pluck('kode_kriteria', 'id_kriteria')->toArray();
        // Map untuk menyimpan sumber data setiap kriteria
        $kriteriaSourceMap = $kriteriaList->pluck('sumber_data', 'kode_kriteria')->toArray();
        $criteriaKeys = $kriteriaList->pluck('kode_kriteria')->toArray();

        // 2. Ambil semua Alternatif (Mahasiswa) dan Penilaian mereka dalam satu query
        // Eager load juga relasi data dinamis (prestasi, sanksi, dll)
        $alternatives = alternatif::with([
                'penilaian' => function ($query) use ($kriteriaList) {
                    $query->whereIn('id_kriteria', $kriteriaList->pluck('id_kriteria'));
                },
                'mahasiswa.prestasi',
                'mahasiswa.sanksi',
                'mahasiswa.pengaduan',
                'mahasiswa.berita'
            ])
            ->where('id_keputusan', $idKeputusan)
            ->get();

        $rawData = [];

        foreach ($alternatives as $alternatif) {
            $dataAlternatif = [
                'id_alternatif' => $alternatif->id_alternatif, 
                'nama' => $alternatif->nama_alternatif,
            ];
            
            foreach ($criteriaKeys as $key) {
                $dataAlternatif[$key] = 0;
            }

            // 3. Isi nilai kriteria
            // Fill with manual/synced values from 'penilaian' table
            foreach ($alternatif->penilaian as $penilaian) {
                $idKriteria = $penilaian->id_kriteria;
                if (isset($kriteriaMap[$idKriteria])) {
                    $kodeKriteria = $kriteriaMap[$idKriteria];
                    $dataAlternatif[$kodeKriteria] = (float) $penilaian->nilai; 
                }
            }
            
            $rawData[] = $dataAlternatif;
        }

        return [
            'alternatives' => $rawData,
            'criteria' => $kriteriaList,
        ];
    }
}