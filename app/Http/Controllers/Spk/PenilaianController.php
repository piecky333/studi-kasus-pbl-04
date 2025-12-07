<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Spk\KeputusanDetailController; 
use App\Models\spkkeputusan;
use App\Models\alternatif;
use App\Models\kriteria;
use App\Models\penilaian;

/**
 * Class PenilaianController
 * 
 * Controller ini bertanggung jawab untuk mengelola Matriks Penilaian (Decision Matrix).
 * Matriks ini berisi nilai dari setiap Alternatif terhadap setiap Kriteria.
 * 
 * Fungsi utama:
 * 1. Menampilkan matriks penilaian (Xij) dalam bentuk tabel grid.
 * 2. Menangani input nilai, baik berupa angka langsung maupun pilihan Sub Kriteria.
 * 3. Menyimpan perubahan nilai secara massal (Bulk Update).
 * 
 * @package App\Http\Controllers\Spk
 */
class PenilaianController extends KeputusanDetailController
{
    /**
     * Constructor.
     * 
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    
    /**
     * Menampilkan halaman Matriks Penilaian (Tab Penilaian).
     * 
     * Mempersiapkan data yang kompleks untuk view:
     * - Daftar Kriteria (Kolom)
     * - Daftar Alternatif (Baris)
     * - Peta Nilai (Sel)
     * - Peta Sub Kriteria (Opsi Dropdown)
     * 
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

        // 3. Membangun Map Sub Kriteria (untuk Dropdown di UI).
        // Struktur: [id_kriteria => [nilai_konversi => nama_subkriteria]]
        // Ini memudahkan view untuk merender <select> tanpa logic yang rumit.
        $subKriteriaMap = [];
        foreach ($kriteriaList as $kriteria) {
            if ($kriteria->subKriteria->count() > 0) {
                $subKriteriaMap[$kriteria->id_kriteria] = $kriteria->subKriteria
                    ->pluck('nama_subkriteria', 'nilai');
            }
        }
        
        // 4. Membangun Matriks Penilaian (Xij).
        // Struktur: [id_alternatif][id_kriteria] = nilai
        // Memungkinkan akses O(1) di view saat merender sel tabel.
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
     * Menyimpan perubahan nilai penilaian secara massal.
     * 
     * Method ini menangani input array multidimensi dari form tabel.
     * Menggunakan `updateOrCreate` untuk menangani insert (jika belum ada) atau update (jika sudah ada).
     * 
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