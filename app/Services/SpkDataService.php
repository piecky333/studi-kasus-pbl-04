<?php

namespace App\Services;

use App\Models\SpkKeputusan;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;

class SpkDataService
{
    /**
     * Mengambil data mentah SPK untuk proses perhitungan.
     *
     * @param int $idKeputusan ID dari SpkKeputusan yang akan diproses.
     * @return array Data yang siap untuk dinormalisasi.
     */
    public function getSpkRawData(int $idKeputusan): array
    {
        $kriteriaList = Kriteria::where('id_keputusan', $idKeputusan)
            ->get(['id_kriteria', 'kode_kriteria', 'jenis_kriteria', 'nama_kriteria', 'sumber_data']);

        $kriteriaMap       = $kriteriaList->pluck('kode_kriteria', 'id_kriteria')->toArray();
        $criteriaKeys      = $kriteriaList->pluck('kode_kriteria')->toArray();

        $alternatives = Alternatif::with([
                'penilaian' => function ($query) use ($kriteriaList) {
                    $query->whereIn('id_kriteria', $kriteriaList->pluck('id_kriteria'));
                },
                'mahasiswa.prestasi',
                'mahasiswa.sanksi',
                'mahasiswa.pengaduan',
                'mahasiswa.berita',
            ])
            ->where('id_keputusan', $idKeputusan)
            ->get();

        $rawData = [];

        foreach ($alternatives as $alternatif) {
            $dataAlternatif = [
                'id_alternatif' => $alternatif->id_alternatif,
                'nama'          => $alternatif->nama_alternatif,
            ];

            foreach ($criteriaKeys as $key) {
                $dataAlternatif[$key] = 0;
            }

            foreach ($alternatif->penilaian as $penilaian) {
                $idKriteria = $penilaian->id_kriteria;
                if (isset($kriteriaMap[$idKriteria])) {
                    $kodeKriteria                    = $kriteriaMap[$idKriteria];
                    $dataAlternatif[$kodeKriteria]   = (float) $penilaian->nilai;
                }
            }

            $rawData[] = $dataAlternatif;
        }

        return [
            'alternatives' => $rawData,
            'criteria'     => $kriteriaList,
        ];
    }
}