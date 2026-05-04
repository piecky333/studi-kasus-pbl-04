<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Spk\KeputusanDetailController;
use App\Models\PerbandinganKriteria;
use App\Models\SpkKeputusan;
use App\Models\Kriteria;
use App\Services\AhpService;
use Illuminate\Support\Facades\Validator;

class PerbandinganKriteriaController extends KeputusanDetailController
{
    protected AhpService $ahpService;

    public function __construct(Request $request, AhpService $ahpService)
    {
        parent::__construct($request);
        $this->ahpService = $ahpService;
    }

    protected function getKriteriaCollection()
    {
        return Kriteria::where('id_keputusan', $this->idKeputusan)
            ->orderBy('kode_kriteria', 'asc')
            ->get()
            ->values();
    }

    protected function getPasanganData()
    {
        $kriteria = $this->getKriteriaCollection();

        $perbandinganTersimpan = PerbandinganKriteria::where('id_keputusan', $this->idKeputusan)->get();

        $mapNilaiDB = [];
        foreach ($perbandinganTersimpan as $item) {
            $mapNilaiDB["{$item->id_kriteria_1}_{$item->id_kriteria_2}"] = $item->nilai_perbandingan;
        }

        $pasangan = [];
        $count    = $kriteria->count();

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $k1   = $kriteria[$i];
                $k2   = $kriteria[$j];
                $k1Id = $k1->id_kriteria;
                $k2Id = $k2->id_kriteria;

                $nilaiPecahan = $mapNilaiDB["{$k1Id}_{$k2Id}"] ?? null;

                if (is_null($nilaiPecahan)) {
                    $nilaiTerbalik = $mapNilaiDB["{$k2Id}_{$k1Id}"] ?? null;
                    if ($nilaiTerbalik) {
                        $nilaiPecahan = 1 / $nilaiTerbalik;
                    } else {
                        $nilaiPecahan = 1;
                    }
                }

                $nilaiUI = 1;

                if ($nilaiPecahan < 1) {
                    $resiprokal = round(1 / $nilaiPecahan);
                    $nilaiUI    = -$resiprokal;
                } elseif ($nilaiPecahan > 1) {
                    $nilaiUI = round($nilaiPecahan);
                }

                $pasangan[] = [
                    'kriteria1'                    => $k1,
                    'kriteria2'                    => $k2,
                    'nilai_perbandingan_tersimpan' => $nilaiUI,
                ];
            }
        }

        return $pasangan;
    }

    protected function getPasanganDataPrioritized(Request $request)
    {
        $pasanganDariDb  = $this->getPasanganData();
        $pasanganBaru    = [];
        $requestPasangan = $request->input('pasangan', []);

        foreach ($pasanganDariDb as $p) {
            $k1Id       = $p['kriteria1']->id_kriteria;
            $k2Id       = $p['kriteria2']->id_kriteria;
            $keyPasangan = "{$k1Id}_{$k2Id}";

            $nilaiDariInput = $requestPasangan[$keyPasangan]['nilai'] ?? old("pasangan.{$keyPasangan}.nilai");
            $nilaiFinal     = $nilaiDariInput ?? ($p['nilai_perbandingan_tersimpan'] ?? 1);

            $pasanganBaru[] = [
                'kriteria1'                    => $p['kriteria1'],
                'kriteria2'                    => $p['kriteria2'],
                'nilai_perbandingan_tersimpan' => $nilaiFinal,
            ];
        }

        return $pasanganBaru;
    }

    public function index(SpkKeputusan $keputusan)
    {
        $hasilAHP = session('hasilAHP');

        $kriteria            = $this->getKriteriaCollection();
        $requestUntukPasangan = new Request();

        if ($hasilAHP && isset($hasilAHP['pasangan_input_terakhir'])) {
            $requestUntukPasangan->merge(['pasangan' => $hasilAHP['pasangan_input_terakhir']]);
        }

        $pasangan = $this->getPasanganDataPrioritized($requestUntukPasangan);

        return view('pages.admin.spk.kriteria.perbandingan.index', [
            'idKeputusan' => $this->idKeputusan,
            'keputusan'   => $this->keputusan,
            'pasangan'    => $pasangan,
            'kriteriaList' => $kriteria,
            'hasilAHP'    => $hasilAHP,
        ]);
    }

    public function save(Request $request, SpkKeputusan $keputusan)
    {
        $idKeputusan = $keputusan->id_keputusan;

        $validator = Validator::make($request->all(), [
            'pasangan'          => 'required|array',
            'pasangan.*.nilai'  => 'required|numeric|min:-9|max:9|not_in:0',
        ], [
            'pasangan.*.nilai.required' => 'Semua perbandingan harus diisi.',
            'pasangan.*.nilai.not_in'   => 'Skala perbandingan tidak boleh nol (0).',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        foreach ($request->pasangan as $pairData) {
            $nilaiPerbandingan = $pairData['nilai'];
            $k1                = $pairData['kriteria1_id'];
            $k2                = $pairData['kriteria2_id'];
            $nilaiDisimpan     = 1;
            $idKriteria1DB     = $k1;
            $idKriteria2DB     = $k2;

            if ($nilaiPerbandingan > 1) {
                $nilaiDisimpan = 1 / $nilaiPerbandingan;
                $idKriteria1DB = $k2;
                $idKriteria2DB = $k1;
            } elseif ($nilaiPerbandingan < 1) {
                $nilaiDisimpan = 1 / abs($nilaiPerbandingan);
            }

            PerbandinganKriteria::where('id_keputusan', $idKeputusan)
                ->where('id_kriteria_1', $idKriteria2DB)
                ->where('id_kriteria_2', $idKriteria1DB)
                ->delete();

            PerbandinganKriteria::updateOrCreate(
                [
                    'id_keputusan' => $idKeputusan,
                    'id_kriteria_1' => $idKriteria1DB,
                    'id_kriteria_2' => $idKriteria2DB,
                ],
                ['nilai_perbandingan' => $nilaiDisimpan]
            );
        }

        return redirect()->route('admin.spk.kriteria.perbandingan.index', $keputusan)
            ->with('success', 'Matriks perbandingan berhasil disimpan!');
    }

    public function checkConsistency(Request $request, SpkKeputusan $keputusan)
    {
        $idKeputusan = $keputusan->id_keputusan;

        $validator = Validator::make($request->all(), [
            'pasangan'         => 'required|array',
            'pasangan.*.nilai' => 'required|numeric|min:-9|max:9|not_in:0',
        ], [
            'pasangan.*.nilai.required' => 'Semua perbandingan harus diisi sebelum Cek Konsistensi.',
            'pasangan.*.nilai.not_in'   => 'Skala perbandingan tidak boleh nol (0).',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $kriteria = $this->getKriteriaCollection();
        $n        = $kriteria->count();

        if ($n < 3) {
            return back()->with('error', 'Diperlukan minimal 3 kriteria untuk perhitungan Konsistensi AHP yang valid.')->withInput();
        }

        $inputNilai = [];
        foreach ($request->pasangan as $pairData) {
            $k1    = $pairData['kriteria1_id'];
            $k2    = $pairData['kriteria2_id'];
            $nilai = $pairData['nilai'];
            $key   = "c{$k1}_{$k2}";
            $inputNilai[$key] = ($nilai < 0) ? (1 / abs($nilai)) : $nilai;
        }

        $matrix     = $this->ahpService->buildMatrix($inputNilai, $n, $kriteria->pluck('id_kriteria')->toArray());
        $ahpResults = $this->ahpService->calculateAhp($matrix, $n);

        $hasilAHP = array_merge($ahpResults, [
            'matrix'                  => $matrix,
            'n'                       => $n,
            'kriteriaList'            => $kriteria,
            'pasangan_input_terakhir' => $request->input('pasangan'),
        ]);

        $successMessage = null;
        $errorMessage   = null;

        if ($hasilAHP['crData']['cr'] <= 0.1) {
            $kriteriaToUpdate = $this->getKriteriaCollection();

            foreach ($kriteriaToUpdate as $index => $k) {
                if (isset($hasilAHP['weights'][$index])) {
                    $k->bobot_kriteria = $hasilAHP['weights'][$index];
                    $k->save();
                }
            }
            $successMessage = 'Perhitungan Konsistensi AHP berhasil dijalankan dan bobot kriteria telah disimpan. CR = ' . number_format($hasilAHP['crData']['cr'], 4);
        } else {
            $errorMessage = 'Matriks Tidak Konsisten (CR = ' . number_format($hasilAHP['crData']['cr'], 4) . ' > 0.10). Harap ulangi perbandingan.';
        }

        return redirect()->route('admin.spk.kriteria.perbandingan.index', $keputusan)
            ->with('success', $successMessage)
            ->with('error', $errorMessage)
            ->with('hasilAHP', $hasilAHP)
            ->withInput($request->all());
    }
}