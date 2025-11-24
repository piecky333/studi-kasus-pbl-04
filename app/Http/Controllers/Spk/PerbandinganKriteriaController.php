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
 * Controller ini mengelola perbandingan kriteria (AHP) dan perhitungan bobot.
 * Mewarisi KeputusanDetailController untuk memastikan konteks Keputusan sudah dimuat.
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
     * Mengambil Kriteria dengan memastikan indeksnya 0, 1, 2, ...
     * Menggunakan properti kelas $this->idKeputusan.
     */
    protected function getKriteriaCollection()
    {
        return kriteria::where('id_keputusan', $this->idKeputusan)
            ->orderBy('kode_kriteria', 'asc')
            ->get()
            ->values();
    }

    /**
     * Mengambil dan memformat data pasangan perbandingan yang tersimpan dari DB.
     * Nilai dikonversi ke format UI (-9 hingga 9).
     * Menggunakan properti kelas $this->idKeputusan.
     * @return array
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
                     $nilaiPecahan = $mapNilaiDB["{$k2Id}_{$k1Id}"] ?? 1;
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
     * Mengambil data pasangan, memprioritaskan nilai dari Request (old input) atau dari DB.
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
     * Menampilkan halaman perbandingan AHP (Tab Kriteria, Sub-halaman AHP).
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
     * Menyimpan matriks perbandingan ke database.
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
     * Memicu perhitungan Konsistensi AHP.
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