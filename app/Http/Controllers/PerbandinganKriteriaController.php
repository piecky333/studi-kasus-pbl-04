<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerbandinganKriteria;
use App\Models\Kriteria;
use App\Services\AhpService;
use Illuminate\Support\Facades\Validator; // Tambahkan ini

class PerbandinganKriteriaController extends Controller
{
    protected AhpService $ahpService;

    public function __construct(AhpService $ahpService)
    {
        $this->ahpService = $ahpService;
    }

    // ==========================================================
    // HELPER METHODS
    // ==========================================================

    /**
     * Mengambil Kriteria dengan memastikan indeksnya 0, 1, 2, ...
     */
    protected function getKriteriaCollection($idKeputusan)
    {
        return Kriteria::where('id_keputusan', $idKeputusan)
            ->orderBy('kode_kriteria', 'asc')
            ->get()
            ->values();
    }

    /**
     * Mengambil dan memformat data pasangan perbandingan yang tersimpan dari DB.
     * Nilai dikonversi ke format UI (-9 hingga 9).
     * @param int $idKeputusan
     * @return array
     */
    protected function getPasanganData($idKeputusan)
    {
        $kriteria = $this->getKriteriaCollection($idKeputusan);

        // Ambil semua data perbandingan yang tersimpan
        $perbandinganTersimpan = PerbandinganKriteria::where('id_keputusan', $idKeputusan)
            ->get();
        
        // Buat map nilai DB: (id_k1_id_k2) => nilai
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

                // Ambil nilai dari DB. Kita asumsikan k1 selalu memiliki ID yang lebih kecil 
                // saat disimpan, jika tidak, kita perlu mencari kedua urutan.
                $nilaiPecahan = $mapNilaiDB["{$k1Id}_{$k2Id}"] ?? null;
                
                // Jika tidak ada data tersimpan di urutan K1_K2, coba urutan K2_K1
                // Ini penting jika logika save Anda sebelumnya tidak menjamin K1 < K2.
                if (is_null($nilaiPecahan)) {
                     $nilaiPecahan = $mapNilaiDB["{$k2Id}_{$k1Id}"] ?? 1; // Default ke 1
                } else {
                     // Jika nilai ditemukan di K1_K2, kita anggap itu nilai yang benar.
                     // Jika nilai pecahan < 1, artinya K2 lebih penting dari K1.
                }

                $nilaiUI = 1; // Default: Sama penting (1)

                // Konversi nilai pecahan (DB) ke nilai UI (-9 sampai 9)
                if ($nilaiPecahan < 1) {
                    // Jika nilai pecahan (< 1, cth: 1/3 = 0.333), artinya Kriteria K1 (sebelah kiri) 
                    // kurang penting dari Kriteria K2 (sebelah kanan).
                    // Nilai UI yang harus muncul adalah -3 (karena 1/0.333 = 3, lalu dijadikan negatif).
                    $resiprokal = round(1 / $nilaiPecahan);
                    $nilaiUI = -$resiprokal;
                } elseif ($nilaiPecahan > 1) {
                    // Seharusnya nilai di DB selalu <= 1 jika urutannya K1 vs K2, 
                    // tetapi jika ada data > 1, kita gunakan nilai tersebut.
                    $nilaiUI = round($nilaiPecahan);
                }
                
                // Jika nilai pecahan = 1, nilaiUI tetap 1.

                $pasangan[] = [
                    'kriteria1' => $k1,
                    'kriteria2' => $k2,
                    'nilai_perbandingan_tersimpan' => $nilaiUI, // Nilai yang akan dicentang di Blade (-9 s/d 9)
                ];
            }
        }

        return $pasangan;
    }

    /**
     * @BARU: Mengambil data pasangan, memprioritaskan nilai dari Request (old input) atau dari DB.
     * Digunakan saat ada redirect dengan withInput() atau saat checkConsistency.
     * @param Request $request
     * @param int $idKeputusan
     * @return array
     */
    protected function getPasanganDataPrioritized(Request $request, $idKeputusan)
    {
        // 1. Ambil data pasangan dasar dari DB
        $pasanganDariDb = $this->getPasanganData($idKeputusan);
        $pasanganBaru = [];
        
        // 2. Ambil input 'pasangan' yang dikirim pada Request sebelumnya
        $requestPasangan = $request->input('pasangan', []);
        
        // 3. Loop dan prioritaskan nilai input lama/request
        foreach ($pasanganDariDb as $p) {
            $k1Id = $p['kriteria1']->id_kriteria;
            $k2Id = $p['kriteria2']->id_kriteria;
            $keyPasangan = "{$k1Id}_{$k2Id}";

            // Ambil nilai dari input yang baru di-submit (untuk checkConsistency)
            // Atau ambil nilai dari old session (untuk save jika ada validation error)
            $nilaiDariInput = $requestPasangan[$keyPasangan]['nilai'] ?? old("pasangan.{$keyPasangan}.nilai");
            
            // Prioritaskan: Nilai Input > Nilai Tersimpan di DB > Default 1
            $nilaiFinal = $nilaiDariInput ?? ($p['nilai_perbandingan_tersimpan'] ?? 1);
            
            $pasanganBaru[] = [
                'kriteria1' => $p['kriteria1'],
                'kriteria2' => $p['kriteria2'],
                // Nilai yang diprioritaskan
                'nilai_perbandingan_tersimpan' => $nilaiFinal, 
            ];
        }

        return $pasanganBaru;
    }

    // ==========================================================
    // CRUD METHODS
    // ==========================================================

    public function index($idKeputusan)
    {
        // Saat index pertama kali dimuat, kita hanya mengambil dari DB
        $kriteria = $this->getKriteriaCollection($idKeputusan);
        // Penting: Gunakan request kosong untuk memastikan hanya memuat dari DB
        $pasangan = $this->getPasanganDataPrioritized(new Request(), $idKeputusan); 

        return view('pages.admin.spk.kriteria.perbandingan.index', [
            'idKeputusan' => $idKeputusan,
            'pasangan' => $pasangan,
            'kriteriaList' => $kriteria,
            'hasilAHP' => null,
        ]);
    }

    public function save(Request $request)
    {
        $idKeputusan = $request->input('id_keputusan');

        // Tambahkan validasi sederhana untuk memastikan semua nilai terisi
        $validator = Validator::make($request->all(), [
            'pasangan' => 'required|array',
            'pasangan.*.nilai' => 'required|numeric|min:-9|max:9|not_in:0',
        ], [
            'pasangan.*.nilai.required' => 'Semua perbandingan harus diisi.',
            'pasangan.*.nilai.not_in' => 'Skala perbandingan tidak boleh nol (0).',
        ]);

        if ($validator->fails()) {
            // Jika validasi gagal, REDIRECT BACK dan BAWA INPUT SEBELUMNYA
            // Ini yang akan membuat pilihan radio button tetap ada saat error.
            return back()->withErrors($validator)->withInput(); 
        }

        // Jika lolos validasi, lakukan penyimpanan ke DB
        foreach ($request->pasangan as $pairData) {
            $nilaiPerbandingan = $pairData['nilai'];
            $k1 = $pairData['kriteria1_id'];
            $k2 = $pairData['kriteria2_id'];
            $nilaiDisimpan = 1; // Default: Sama penting (1)
            $idKriteria1DB = $k1;
            $idKriteria2DB = $k2;

            if ($nilaiPerbandingan > 1) {
                // Kriteria A (k1) lebih penting dari Kriteria B (k2)
                // Kita simpan nilai resiprokal (pecahan) dengan urutan K2 vs K1
                $nilaiDisimpan = 1 / $nilaiPerbandingan;
                $idKriteria1DB = $k2; // K2 menjadi Kriteria 1
                $idKriteria2DB = $k1; // K1 menjadi Kriteria 2
            } elseif ($nilaiPerbandingan < 1) {
                // Kriteria B (k2) lebih penting dari Kriteria A (k1)
                // Nilai negatif harus dijadikan pecahan positif. Urutan kriteria tetap K1 vs K2.
                $nilaiDisimpan = 1 / abs($nilaiPerbandingan);
                // $idKriteria1DB dan $idKriteria2DB tetap $k1 dan $k2
            }
            // Jika $nilaiPerbandingan = 1, $nilaiDisimpan tetap 1.

            // SIMPAN ke DB
            PerbandinganKriteria::updateOrCreate(
                [
                    'id_keputusan' => $idKeputusan,
                    // Selalu simpan dengan ID terkecil sebagai Kriteria 1 (untuk konsistensi),
                    // atau ikuti aturan yang sudah ditentukan di sini (K1_DB vs K2_DB)
                    'id_kriteria_1' => $idKriteria1DB, 
                    'id_kriteria_2' => $idKriteria2DB, 
                ],
                [
                    'nilai_perbandingan' => $nilaiDisimpan, 
                ]
            );
        }

        // Setelah penyimpanan berhasil, redirect kembali.
        return redirect()->route('admin.spk.perbandingan.index', $idKeputusan)
            ->with('success', 'Matriks perbandingan berhasil disimpan!');
    }

    public function checkConsistency(Request $request, $idKeputusan)
    {
        // 1. Validasi Input (Wajib!)
        $validator = Validator::make($request->all(), [
            'pasangan' => 'required|array',
            'pasangan.*.nilai' => 'required|numeric|min:-9|max:9|not_in:0',
        ], [
            'pasangan.*.nilai.required' => 'Semua perbandingan harus diisi sebelum Cek Konsistensi.',
            'pasangan.*.nilai.not_in' => 'Skala perbandingan tidak boleh nol (0).',
        ]);

        if ($validator->fails()) {
            // Jika validasi gagal, REDIRECT BACK dan BAWA INPUT SEBELUMNYA
            return back()->withErrors($validator)->withInput(); 
        }

        // 2. Dapatkan daftar kriteria (untuk N dan map ID)
        $kriteria = $this->getKriteriaCollection($idKeputusan);
        $n = $kriteria->count();

        $kriteriaIdMapByIndex = $kriteria->pluck('id_kriteria')->toArray();

        if ($n < 3) {
            return back()->with('error', 'Diperlukan minimal 3 kriteria untuk perhitungan Konsistensi AHP yang valid.')->withInput();
        }

        // 3. Ambil nilai dari Request dan buat $inputNilai (Matriks Asli)
        $inputNilai = [];
        foreach ($request->pasangan as $pairData) {
            $k1 = $pairData['kriteria1_id'];
            $k2 = $pairData['kriteria2_id'];
            $nilai = $pairData['nilai'];

            $key = "c{$k1}_{$k2}";
            // Konversi nilai UI (-9 sampai 9) ke nilai Matriks AHP (pecahan 1/9 sampai 9)
            // Jika nilai < 0, artinya 1/|nilai|, contoh: -3 menjadi 1/3
            $inputNilai[$key] = ($nilai < 0) ? (1 / abs($nilai)) : $nilai; 
        }

        // 4. Hitung AHP menggunakan AhpService
        $matrix = $this->ahpService->buildMatrix($inputNilai, $n, $kriteriaIdMapByIndex);
        $ahpResults = $this->ahpService->calculateAhp($matrix, $n);

        // 5. Kumpulkan hasil
        $hasilAHP = array_merge($ahpResults, [
            'matrix' => $matrix,
            'n' => $n,
            'kriteriaList' => $kriteria,
        ]);
        
        $successMessage = null;
        $errorMessage = null;

        // 6. UPDATE BOBOT KRITERIA JIKA KONSISTEN
        if ($hasilAHP['crData']['cr'] <= 0.1) {
            $kriteriaToUpdate = $this->getKriteriaCollection($idKeputusan);

            foreach ($kriteriaToUpdate as $index => $k) {
                if (isset($hasilAHP['weights'][$index])) {
                    $k->bobot_kriteria = $hasilAHP['weights'][$index];
                    $k->save();
                }
            }
            $successMessage = 'Perhitungan Konsistensi AHP berhasil dijalankan dan bobot kriteria telah disimpan di database. CR = ' . number_format($hasilAHP['crData']['cr'], 4);

        } else {
            $errorMessage = 'Matriks Tidak Konsisten (CR = ' . number_format($hasilAHP['crData']['cr'], 4) . ' > 0.10). Harap ulangi perbandingan.';
        }

        // 7. Kembalikan ke view perbandingan tanpa redirect
        return view('pages.admin.spk.kriteria.perbandingan.index', [
            'idKeputusan' => $idKeputusan,
            // PENTING: Gunakan helper untuk mengambil nilai dari REQUEST (input yang baru disubmit)
            'pasangan' => $this->getPasanganDataPrioritized($request, $idKeputusan), 
            'kriteriaList' => $kriteria,
            'hasilAHP' => $hasilAHP,
        ])->with('success', $successMessage)
            ->with('error', $errorMessage);
    }
}