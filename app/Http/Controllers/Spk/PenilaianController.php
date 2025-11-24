<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Spk\KeputusanDetailController; 
use App\Models\spkkeputusan;
use App\Models\alternatif;
use App\Models\kriteria;
use App\Models\penilaian;

/**
 * Controller ini mengelola Matriks Penilaian (Xij) untuk semua Alternatif dan Kriteria.
 * Mewarisi KeputusanDetailController untuk memastikan konteks Keputusan sudah dimuat.
 */
class PenilaianController extends KeputusanDetailController
{
    /**
     * Constructor untuk memuat Keputusan induk.
     * Logika findOrFail dijalankan di parent.
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    
    /**
     * Menampilkan Matriks Penilaian (Xij) dalam format tabel.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Data Keputusan tersedia via $this->keputusan
        
        // 1. Ambil semua Kriteria (termasuk relasi subkriteria)
        $kriteriaList = kriteria::where('id_keputusan', $this->idKeputusan)
                                ->with('subKriteria')
                                ->orderBy('kode_kriteria')
                                ->get();

        // 2. Ambil semua Alternatif (termasuk relasi penilaian)
        $alternatifData = alternatif::where('id_keputusan', $this->idKeputusan)
                                    ->with('penilaian')
                                    ->get();

        // 3. Konversi Sub Kriteria ke map untuk display di view (misal: menampilkan nama sub kriteria)
        $subKriteriaMap = [];
        foreach ($kriteriaList as $kriteria) {
            // Hanya proses jika kriteria menggunakan subkriteria (cara_penilaian yang diatur)
            if ($kriteria->subKriteria->count() > 0) {
                // Buat map [nilai_konversi => nama_subkriteria]
                // Catatan: Pastikan kolom 'nilai_konversi' ada di model subkriteria Anda.
                $subKriteriaMap[$kriteria->id_kriteria] = $kriteria->subKriteria
                    ->pluck('nama_subkriteria', 'nilai');
            }
        }
        
        // 4. Konversi Penilaian ke map [id_alternatif][id_kriteria] = nilai (Matriks Xij)
        $penilaianMatrix = [];
        foreach ($alternatifData as $alternatif) {
            foreach ($alternatif->penilaian as $penilaian) {
                $penilaianMatrix[$alternatif->id_alternatif][$penilaian->id_kriteria] = $penilaian->nilai;
            }
        }

        return view('pages.admin.spk.penilaian.index', [
            'idKeputusan' => $this->idKeputusan,
            'keputusan' => $this->keputusan,
            'kriteriaList' => $kriteriaList,
            'alternatifData' => $alternatifData,
            'penilaianMatrix' => $penilaianMatrix,
            'subKriteriaMap' => $subKriteriaMap,
            'pageTitle' => 'Manajemen Matriks Penilaian'
        ]);
    }

    /**
     * Menyimpan pembaruan Matriks Penilaian secara massal.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Validasi massal
        $request->validate([
            'nilai_penilaian' => 'required|array',
            'nilai_penilaian.*.*' => 'required|numeric', // Tidak membatasi min:0, karena nilai bisa negatif 
        ], [
            'nilai_penilaian.required' => 'Setidaknya satu nilai penilaian harus diisi.',
            'nilai_penilaian.*.*.required' => 'Semua nilai penilaian harus diisi.',
            'nilai_penilaian.*.*.numeric' => 'Nilai penilaian harus berupa angka.',
        ]);

        $updatesCount = 0;
        
        // Melakukan update massal melalui updateOrCreate
        foreach ($request->nilai_penilaian as $idAlternatif => $penilaianPerAlternatif) {
            foreach ($penilaianPerAlternatif as $idKriteria => $nilai) {
                // Mencari atau membuat 
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

        return redirect()->route('admin.spk.alternatif.penilaian.index', $this->idKeputusan)
                         ->with('success', "{$updatesCount} nilai penilaian berhasil diperbarui.");
    }
}