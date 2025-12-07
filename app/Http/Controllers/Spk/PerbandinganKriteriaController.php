<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Spk\KeputusanDetailController; // PENTING: Import Base Controller SPK
use App\Models\PerbandinganKriteria;
use App\Models\kriteria; // Menggunakan nama model yang benar
use App\Services\AhpService;
use Illuminate\Support\Facades\Validator;
use App\Models\spkkeputusan; // Diperlukan untuk passing data Keputusan ke view

/**
 * Class PerbandinganKriteriaController
 * 
 * Controller ini bertanggung jawab untuk mengelola proses Analytical Hierarchy Process (AHP)
 * untuk menentukan bobot kriteria.
 * 
 * Fitur utama:
 * 1. Menampilkan matriks perbandingan berpasangan.
 * 2. Menyimpan nilai perbandingan ke database.
 * 3. Menghitung Konsistensi Ratio (CR) dan Bobot Prioritas (Eigen Vector).
 * 
 * @package App\Http\Controllers\Spk
 */
class PerbandinganKriteriaController extends KeputusanDetailController
{
    protected AhpService $ahpService;
    
    // NOTE: $idKeputusan dan $keputusan sudah di-handle oleh parent

    /**
     * Constructor untuk memuat Keputusan induk dan menginjeksikan service.
     */
    public function __construct(Request $request, AhpService $ahpService)
    {
        // 1. Panggil constructor parent untuk memuat Keputusan
        parent::__construct($request); 
        
        // 2. Injeksikan Service
        $this->ahpService = $ahpService;
    }

    // ==========================================================
    // HELPER METHODS (Disesuaikan untuk menggunakan $this->idKeputusan)
    // ==========================================================

    /**
     * Mengambil koleksi Kriteria yang terurut dan di-index ulang.
     * 
     * Penting: Index array harus numerik berurutan (0, 1, 2...) agar sesuai dengan
     * logika iterasi matriks ($i, $j) dalam algoritma AHP.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getKriteriaCollection()
    {
        return kriteria::where('id_keputusan', $this->idKeputusan)
            ->orderBy('kode_kriteria', 'asc')
            ->get()
            ->values();
    }

    /**
     * Mengambil data pasangan perbandingan dari database dan memformatnya untuk UI.
     * 
     * Konsep:
     * - Data di DB disimpan dalam bentuk pecahan desimal (misal: 0.3333 atau 3.0000).
     * - Data di UI (Slider/Input) menggunakan skala Saaty (-9 s.d 9).
     * - Method ini mengkonversi nilai DB -> nilai UI.
     * 
     * @return array Array berisi pasangan kriteria dan nilai perbandingannya.
     */
    protected function getPasanganData()
    {
        $kriteria = $this->getKriteriaCollection(); // Tidak perlu parameter

        // Ambil semua data perbandingan yang tersimpan
        $perbandinganTersimpan = PerbandinganKriteria::where('id_keputusan', $this->idKeputusan)
            ->get();
        
        // ... (Logika mapping dan konversi nilai tetap sama) ...
        $mapNilaiDB = [];
        foreach ($perbandinganTersimpan as $item) {
            $mapNilaiDB["{$item->id_kriteria_1}_{$item->id_kriteria_2}"] = $item->nilai_perbandingan;
        }

        $pasangan = [];
        $count = $kriteria->count();

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $k1 = $kriteria[$i];
                $k2 = $kriteria[$j];
                $k1Id = $k1->id_kriteria;
                $k2Id = $k2->id_kriteria;

                $nilaiPecahan = $mapNilaiDB["{$k1Id}_{$k2Id}"] ?? null;
                
                if (is_null($nilaiPecahan)) {
                     // Jika tidak ada data K1 -> K2, coba cek kebalikannya K2 -> K1.
                     $nilaiTerbalik = $mapNilaiDB["{$k2Id}_{$k1Id}"] ?? null;
                     if ($nilaiTerbalik) {
                         // Sifat Resiprokal AHP: Jika A > B = 3, maka B > A = 1/3.
                         $nilaiPecahan = 1 / $nilaiTerbalik;
                     } else {
                         // Default: Jika belum ada data, asumsikan sama penting (nilai 1).
                         $nilaiPecahan = 1;
                     }
                }

                $nilaiUI = 1;

                if ($nilaiPecahan < 1) {
                    $resiprokal = round(1 / $nilaiPecahan);
                    $nilaiUI = -$resiprokal;
                } elseif ($nilaiPecahan > 1) {
                    $nilaiUI = round($nilaiPecahan);
                }
                
                $pasangan[] = [
                    'kriteria1' => $k1,
                    'kriteria2' => $k2,
                    'nilai_perbandingan_tersimpan' => $nilaiUI,
                ];
            }
        }

        return $pasangan;
    }

    /**
     * Mengambil data pasangan dengan strategi prioritas sumber data.
     * 
     * Digunakan untuk menangani kondisi "Old Input" saat validasi gagal atau setelah perhitungan.
     * Prioritas:
     * 1. Input dari Request (jika user baru saja submit form).
     * 2. Data tersimpan di Database (jika baru buka halaman).
     * 3. Default (1).
     * 
     * @param Request $request
     * @return array
     */
    protected function getPasanganDataPrioritized(Request $request)
    {
        // 1. Ambil data pasangan dasar dari DB
        $pasanganDariDb = $this->getPasanganData(); // Tidak perlu parameter
        $pasanganBaru = [];
        
        // 2. Ambil input 'pasangan' yang dikirim pada Request sebelumnya
        $requestPasangan = $request->input('pasangan', []);
        
        // 3. Loop dan prioritaskan nilai input lama/request
        foreach ($pasanganDariDb as $p) {
            $k1Id = $p['kriteria1']->id_kriteria;
            $k2Id = $p['kriteria2']->id_kriteria;
            $keyPasangan = "{$k1Id}_{$k2Id}";

            $nilaiDariInput = $requestPasangan[$keyPasangan]['nilai'] ?? old("pasangan.{$keyPasangan}.nilai");
            
            // Prioritaskan: Nilai Input > Nilai Tersimpan di DB > Default 1
            $nilaiFinal = $nilaiDariInput ?? ($p['nilai_perbandingan_tersimpan'] ?? 1);
            
            $pasanganBaru[] = [
                'kriteria1' => $p['kriteria1'],
                'kriteria2' => $p['kriteria2'],
                'nilai_perbandingan_tersimpan' => $nilaiFinal, 
            ];
        }

        return $pasanganBaru;
    }

    // ==========================================================
    // CONTROLLER METHODS (Menggunakan properti $this->idKeputusan)
    // ==========================================================

    /**
     * Menampilkan halaman utama Perbandingan Kriteria (AHP).
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil hasil AHP dari flash session, jika ada
        $hasilAHP = session('hasilAHP'); 

        $kriteria = $this->getKriteriaCollection();
        
        // Membuat Request palsu untuk memuat input lama jika ada redirect/error
        $requestUntukPasangan = new Request();
        if ($hasilAHP && isset($hasilAHP['pasangan_input_terakhir'])) {
            $requestUntukPasangan->merge(['pasangan' => $hasilAHP['pasangan_input_terakhir']]);
        }
        
        // Prioritaskan nilai: Old Input > Request AHP > DB
        $pasangan = $this->getPasanganDataPrioritized($requestUntukPasangan); 
        
        // PENTING: Meneruskan $keputusan untuk detail_base.blade.php
        return view('pages.admin.spk.kriteria.perbandingan.index', [
            'idKeputusan' => $this->idKeputusan,
            'keputusan' => $this->keputusan, 
            'pasangan' => $pasangan,
            'kriteriaList' => $kriteria,
            'hasilAHP' => $hasilAHP,
        ]);
    }

    /**
     * Menyimpan nilai perbandingan kriteria ke database.
     * 
     * Method ini menangani konversi dari skala UI (-9 s.d 9) menjadi nilai desimal
     * yang sesuai untuk matriks AHP (1/x atau x).
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        // Gunakan properti kelas $this->idKeputusan
        $idKeputusan = $this->idKeputusan; 

        // 1. Validasi
        $validator = Validator::make($request->all(), [
            'pasangan' => 'required|array',
            'pasangan.*.nilai' => 'required|numeric|min:-9|max:9|not_in:0',
        ], [
            'pasangan.*.nilai.required' => 'Semua perbandingan harus diisi.',
            'pasangan.*.nilai.not_in' => 'Skala perbandingan tidak boleh nol (0).',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput(); 
        }

        // 2. Proses Penyimpanan ke DB (Logika konversi tetap sama)
        foreach ($request->pasangan as $pairData) {
            $nilaiPerbandingan = $pairData['nilai'];
            $k1 = $pairData['kriteria1_id'];
            $k2 = $pairData['kriteria2_id'];
            $nilaiDisimpan = 1;
            $idKriteria1DB = $k1;
            $idKriteria2DB = $k2;

            if ($nilaiPerbandingan > 1) {
                $nilaiDisimpan = 1 / $nilaiPerbandingan;
                $idKriteria1DB = $k2;
                $idKriteria2DB = $k1;
            } elseif ($nilaiPerbandingan < 1) {
                $nilaiDisimpan = 1 / abs($nilaiPerbandingan);
            }

            // Hapus pasangan kebalikannya (K2_K1) jika ada.
            // Kita hanya perlu menyimpan satu sisi perbandingan untuk menghemat space dan menghindari inkonsistensi.
            // Sisi lainnya bisa didapat dengan rumus 1/x saat pengambilan data.
            PerbandinganKriteria::where('id_keputusan', $idKeputusan)
                ->where('id_kriteria_1', $idKriteria2DB)
                ->where('id_kriteria_2', $idKriteria1DB)
                ->delete();

            // SIMPAN/UPDATE ke DB
            PerbandinganKriteria::updateOrCreate(
                [
                    'id_keputusan' => $idKeputusan,
                    'id_kriteria_1' => $idKriteria1DB, 
                    'id_kriteria_2' => $idKriteria2DB, 
                ],
                [
                    'nilai_perbandingan' => $nilaiDisimpan, 
                ]
            );
        }

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.spk.kriteria.perbandingan.index', $idKeputusan)
            ->with('success', 'Matriks perbandingan berhasil disimpan!');
    }

    /**
     * Menjalankan perhitungan Konsistensi AHP dan memperbarui bobot kriteria.
     * 
     * Alur Proses:
     * 1. Validasi input matriks.
     * 2. Hitung Eigen Vector dan Consistency Ratio (CR) menggunakan AhpService.
     * 3. Jika Konsisten (CR <= 0.1): Simpan bobot baru ke tabel Kriteria.
     * 4. Jika Tidak Konsisten: Tampilkan pesan error dan minta user input ulang.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkConsistency(Request $request)
    {
        // 1. Validasi Input
        $idKeputusan = $this->idKeputusan; 

        $validator = Validator::make($request->all(), [
            'pasangan' => 'required|array',
            'pasangan.*.nilai' => 'required|numeric|min:-9|max:9|not_in:0',
        ], [
            'pasangan.*.nilai.required' => 'Semua perbandingan harus diisi sebelum Cek Konsistensi.',
            'pasangan.*.nilai.not_in' => 'Skala perbandingan tidak boleh nol (0).',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput(); 
        }

        // 2. Dapatkan daftar kriteria (untuk N dan map ID)
        $kriteria = $this->getKriteriaCollection(); // Menggunakan helper tanpa parameter
        $n = $kriteria->count();

        if ($n < 3) {
            return back()->with('error', 'Diperlukan minimal 3 kriteria untuk perhitungan Konsistensi AHP yang valid.')->withInput();
        }

        // 3. Ambil nilai dari Request
        $inputNilai = [];
        foreach ($request->pasangan as $pairData) {
            $k1 = $pairData['kriteria1_id'];
            $k2 = $pairData['kriteria2_id'];
            $nilai = $pairData['nilai'];

            $key = "c{$k1}_{$k2}";
            $inputNilai[$key] = ($nilai < 0) ? (1 / abs($nilai)) : $nilai; 
        }

        // 4. Hitung AHP menggunakan AhpService
        $matrix = $this->ahpService->buildMatrix($inputNilai, $n, $kriteria->pluck('id_kriteria')->toArray());
        $ahpResults = $this->ahpService->calculateAhp($matrix, $n);

        // 5. Kumpulkan hasil
        $hasilAHP = array_merge($ahpResults, [
            'matrix' => $matrix,
            'n' => $n,
            'kriteriaList' => $kriteria,
            'pasangan_input_terakhir' => $request->input('pasangan'),
        ]);
        
        $successMessage = null;
        $errorMessage = null;

        // 6. UPDATE BOBOT KRITERIA JIKA KONSISTEN
        if ($hasilAHP['crData']['cr'] <= 0.1) {
            $kriteriaToUpdate = $this->getKriteriaCollection();

            foreach ($kriteriaToUpdate as $index => $k) {
                if (isset($hasilAHP['weights'][$index])) {
                    // Update bobot kriteria di database
                    $k->bobot_kriteria = $hasilAHP['weights'][$index];
                    $k->save();
                }
            }
            $successMessage = 'Perhitungan Konsistensi AHP berhasil dijalankan dan bobot kriteria telah disimpan di database. CR = ' . number_format($hasilAHP['crData']['cr'], 4);

        } else {
            $errorMessage = 'Matriks Tidak Konsisten (CR = ' . number_format($hasilAHP['crData']['cr'], 4) . ' > 0.10). Harap ulangi perbandingan.';
        }

        // 7. Redirect ke halaman index dengan hasil AHP disimpan di session
        return redirect()->route('admin.spk.kriteria.perbandingan.index', $idKeputusan)
            ->with('success', $successMessage)
            ->with('error', $errorMessage)
            ->with('hasilAHP', $hasilAHP) 
            ->withInput($request->all());
    }
}