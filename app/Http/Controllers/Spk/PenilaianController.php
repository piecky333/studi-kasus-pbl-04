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
                $subKriteriaMap[$kriteria->id_kriteria] = $kriteria->subKriteria->map(function ($item) {
                    return [
                        'nilai' => $item->nilai,
                        'nama' => $item->nama_subkriteria
                    ];
                });            }
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
    /**
     * Menyimpan data penilaian baru (First Time Store).
     */
    public function store(Request $request)
    {
        return $this->processSave($request, 'menyimpan');
    }

    /**
     * Memperbarui data penilaian (Update).
     */
    public function update(Request $request)
    {
        return $this->processSave($request, 'memperbarui');
    }

    /**
     * Shared logic untuk menyimpan/update data penilaian.
     */
    private function processSave(Request $request, $actionVerb)
    {
        // Validasi massal
        $request->validate([
            'nilai_penilaian' => 'required|array',
            'nilai_penilaian.*.*' => 'required|numeric', 
        ], [
            'nilai_penilaian.required' => 'Setidaknya satu nilai penilaian harus diisi.',
            'nilai_penilaian.*.*.required' => 'Semua nilai penilaian harus diisi.',
            'nilai_penilaian.*.*.numeric' => 'Nilai penilaian harus berupa angka.',
        ]);

        $updatesCount = 0;
        
        // Melakukan update massal melalui updateOrCreate
        foreach ($request->nilai_penilaian as $idAlternatif => $penilaianPerAlternatif) {
            foreach ($penilaianPerAlternatif as $idKriteria => $nilai) {
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

        return redirect()->back()->with('success', "Berhasil {$actionVerb} {$updatesCount} data penilaian.");
    }

    /**
     * Menyinkronkan nilai penilaian secara otomatis dari data master (Prestasi, Sanksi, IPK).
     *
     * Logika mapping:
     * - Kriteria "Prestasi" (case-insensitive) -> Count valid prestasi from 'admin.prestasi' table
     * - Kriteria "Sanksi" (case-insensitive) -> Count records from 'admin.sanksi' table
     * - Kriteria "IPK" (case-insensitive) -> Value from 'mahasiswa.ipk' column
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Menyinkronkan nilai penilaian secara otomatis dari data master.
     * Menggunakan App\Services\SpkCalculator untuk logika kompleks.
     */
    public function syncScores()
    {
        $kriteriaList = kriteria::where('id_keputusan', $this->idKeputusan)->get();
        $alternatifList = alternatif::where('id_keputusan', $this->idKeputusan)->get();
        $countUpdated = 0;

        foreach ($alternatifList as $alternatif) {
            // Find linked mahasiswa
            $mahasiswa = \App\Models\admin\Datamahasiswa::where('nim', $alternatif->kode_alternatif)->first();
            if (!$mahasiswa) {
                $mahasiswa = \App\Models\admin\Datamahasiswa::where('nama', $alternatif->nama_alternatif)->first();
            }
            if (!$mahasiswa) { continue; }

            // Loading Prestasi (Optimized)
            $prestasiValid = $mahasiswa->prestasi()->where('status_validasi', 'disetujui')->get();

            foreach ($kriteriaList as $kriteria) {
                $source = $kriteria->sumber_data;
                $kriteriaName = strtolower($kriteria->nama_kriteria);
                $value = 0;
                $shouldSync = false;

                // 1. Logika Mahasiswa (IPK, Semester, dll)
                if ($source === 'Mahasiswa' && $kriteria->atribut_sumber) {
                    $attr = $kriteria->atribut_sumber;
                    $value = $mahasiswa->$attr ?? 0;
                    $shouldSync = true;
                }
                
                // 2. Logika Prestasi (Complex Calculation)
                elseif ($source === 'Prestasi') {
                    // Cek nama kriteria untuk menentukan jenis perhitungan
                    if (str_contains($kriteriaName, 'tingkat') || str_contains($kriteriaName, 'level')) {
                         $value = \App\Services\SpkCalculator::calculateTingkatScore($prestasiValid);
                    } 
                    elseif (str_contains($kriteriaName, 'juara') || str_contains($kriteriaName, 'rank') || str_contains($kriteriaName, 'medali')) {
                         $value = \App\Services\SpkCalculator::calculateJuaraScore($prestasiValid);
                    }
                    elseif (str_contains($kriteriaName, 'jumlah') || str_contains($kriteriaName, 'total')) {
                         $value = $prestasiValid->count();
                    } else {
                        // Default Fallback: Count
                        $value = $prestasiValid->count();
                    }
                    $shouldSync = true;
                }

                // 3. Logika Sanksi
                elseif ($source === 'Sanksi') {
                     $value = $mahasiswa->sanksi()->count();
                     $shouldSync = true;
                }
                
                if ($shouldSync) {
                    penilaian::updateOrCreate(
                        ['id_alternatif' => $alternatif->id_alternatif, 'id_kriteria' => $kriteria->id_kriteria],
                        ['nilai' => $value]
                    );
                    $countUpdated++;
                }
            }
        }

        return redirect()->back()->with('success', "Sinkronisasi selesai. {$countUpdated} nilai berhasil diperbarui.");
    }
}