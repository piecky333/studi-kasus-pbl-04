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
    public function syncScores()
    {
        // 1. Identifikasi Kriteria ID berdasarkan nama
        $kriteriaList = kriteria::where('id_keputusan', $this->idKeputusan)->get();
        
        $kriteriaMap = [];
        foreach ($kriteriaList as $kriteria) {
            $nama = strtolower($kriteria->nama_kriteria);
            if (str_contains($nama, 'prestasi')) {
                $kriteriaMap['prestasi'] = $kriteria->id_kriteria;
            } elseif (str_contains($nama, 'sanksi')) {
                $kriteriaMap['sanksi'] = $kriteria->id_kriteria;
            } elseif (str_contains($nama, 'ipk')) {
                $kriteriaMap['ipk'] = $kriteria->id_kriteria;
            }
        }

        if (empty($kriteriaMap)) {
            return redirect()->back()->with('error', 'Tidak ditemukan kriteria dengan nama "Prestasi", "Sanksi", atau "IPK". Pastikan nama kriteria sesuai.');
        }

        // 2. Ambil Data Mahasiswa (Alternatif)
        $alternatifList = alternatif::where('id_keputusan', $this->idKeputusan)->get();
        $countUpdated = 0;

        foreach ($alternatifList as $alternatif) {
            // Asumsi: Kita bisa link kembali ke data mahasiswa asli
            // Idealnya tabel alternatif menyimpan id_mahasiswa. 
            // Jika tidak, kita harus menebak berdasarkan nama atau kode yang sama dengan NIM.
            // Mari kita coba cari mahasiswa berdasarkan kode_alternatif (ASUMSI KODE ALTERNATIF = NIM)
            // Atau jika tabel alternatif punya kolom id_mahasiswa.
            
            // Cek struktur tabel alternatif dulu atau gunakan logic pencocokan
            // Untuk saat ini, asumsikan kode_alternatif adalah NIM atau kita cari berdasarkan nama
            
            $mahasiswa = \App\Models\admin\Datamahasiswa::where('nim', $alternatif->kode_alternatif)->first();

            if (!$mahasiswa) {
                // Fallback: Try match by name
                $mahasiswa = \App\Models\admin\Datamahasiswa::where('nama', $alternatif->nama_alternatif)->first();
            }

            if ($mahasiswa) {
                // Calculate Values
                // Prestasi: Hitung yang valid
                $prestasiCount = $mahasiswa->prestasi()->where('status_validasi', 'valid')->count();
                // Jika status validasi menggunakan kata lain, sesuaikan (misal 'disetujui')
                // Cek enum status_validasi di database atau model, step sebelumnya melihat 'disetujui'
                $prestasiCount = $mahasiswa->prestasi()->where('status_validasi', 'disetujui')->count();

                // Sanksi: Count all
                $sanksiCount = $mahasiswa->sanksi()->count();

                // IPK
                $ipkValue = $mahasiswa->ipk ?? 0;

                // Sync Prestasi
                if (isset($kriteriaMap['prestasi'])) {
                    penilaian::updateOrCreate(
                        ['id_alternatif' => $alternatif->id_alternatif, 'id_kriteria' => $kriteriaMap['prestasi']],
                        ['nilai' => $prestasiCount]
                    );
                    $countUpdated++;
                }

                // Sync Sanksi
                if (isset($kriteriaMap['sanksi'])) {
                    penilaian::updateOrCreate(
                        ['id_alternatif' => $alternatif->id_alternatif, 'id_kriteria' => $kriteriaMap['sanksi']],
                        ['nilai' => $sanksiCount]
                    );
                    $countUpdated++;
                }

                // Sync IPK
                if (isset($kriteriaMap['ipk'])) {
                    penilaian::updateOrCreate(
                        ['id_alternatif' => $alternatif->id_alternatif, 'id_kriteria' => $kriteriaMap['ipk']],
                        ['nilai' => $ipkValue]
                    );
                    $countUpdated++;
                }
            }
        }

        return redirect()->back()->with('success', "Sinkronisasi selesai. {$countUpdated} nilai berhasil diperbarui berdasarkan data master.");
    }
}