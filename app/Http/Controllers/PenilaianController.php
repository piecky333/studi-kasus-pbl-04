<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spkkeputusan;
use App\Models\alternatif;
use App\Models\kriteria;
use App\Models\penilaian;

/**
 * Mengelola Matriks Penilaian (Xij) untuk semua Alternatif dan Kriteria.
 */
class PenilaianController extends Controller
{
    /**
     * Menampilkan Matriks Penilaian.
     */
    public function index($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        
        // 1. Ambil semua Kriteria (termasuk relasi subkriteria)
        $kriteriaList = kriteria::where('id_keputusan', $idKeputusan)
                                ->with('subKriteria')
                                ->orderBy('kode_kriteria')
                                ->get();

        // 2. Ambil semua Alternatif (termasuk relasi penilaian)
        $alternatifData = alternatif::where('id_keputusan', $idKeputusan)
                                    ->with('penilaian')
                                    ->get();

        // 3. Konversi Sub Kriteria ke map untuk display di view
        $subKriteriaMap = [];
        foreach ($kriteriaList as $kriteria) {
            if ($kriteria->subKriteria->count() > 0) {
                // Buat map [nilai_konversi => nama_subkriteria]
                $subKriteriaMap[$kriteria->id_kriteria] = $kriteria->subKriteria
                    ->pluck('nama_subkriteria', 'nilai_konversi');
            }
        }
        
        // 4. Konversi Penilaian ke map [id_alternatif][id_kriteria] = nilai
        $penilaianMatrix = [];
        foreach ($alternatifData as $alternatif) {
            foreach ($alternatif->penilaian as $penilaian) {
                $penilaianMatrix[$alternatif->id_alternatif][$penilaian->id_kriteria] = $penilaian->nilai;
            }
        }

        return view('pages.admin.spk.penilaian.index', [
            'keputusan' => $keputusan,
            'kriteriaList' => $kriteriaList,
            'alternatifData' => $alternatifData,
            'penilaianMatrix' => $penilaianMatrix,
            'subKriteriaMap' => $subKriteriaMap,
            'pageTitle' => 'Manajemen Matriks Penilaian'
        ]);
    }

    /**
     * Menyimpan pembaruan Matriks Penilaian secara massal.
     */
    public function update(Request $request, $idKeputusan)
    {
        // Validasi massal
        $request->validate([
            'nilai_penilaian' => 'required|array',
            'nilai_penilaian.*.*' => 'required|numeric|min:0', // nilai_penilaian[alt_id][krit_id]
        ]);

        $updatesCount = 0;
        
        foreach ($request->nilai_penilaian as $idAlternatif => $penilaianPerAlternatif) {
            foreach ($penilaianPerAlternatif as $idKriteria => $nilai) {
                // Mencari atau membuat entri penilaian (seharusnya sudah ada saat alternatif/kriteria dibuat)
                penilaian::updateOrCreate(
                    [
                        'id_alternatif' => $idAlternatif,
                        'id_kriteria' => $idKriteria,
                    ],
                    [
                        'nilai' => (float) $nilai,
                    ]
                );
                $updatesCount++;
            }
        }

        return redirect()->route('penilaian.index', $idKeputusan)
                         ->with('success', "{$updatesCount} nilai penilaian berhasil diperbarui.");
    }
}