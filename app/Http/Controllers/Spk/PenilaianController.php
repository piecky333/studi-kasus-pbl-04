<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Spk\KeputusanDetailController;
use App\Models\SpkKeputusan;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;

class PenilaianController extends KeputusanDetailController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index(SpkKeputusan $keputusan)
    {
        $kriteriaList = Kriteria::where('id_keputusan', $this->idKeputusan)
                                ->with('subKriteria')
                                ->orderBy('kode_kriteria')
                                ->get();

        $alternatifData = Alternatif::where('id_keputusan', $this->idKeputusan)
                                    ->with('penilaian')
                                    ->get();

        $subKriteriaMap = [];
        foreach ($kriteriaList as $kriteria) {
            if ($kriteria->subKriteria->count() > 0) {
                $subKriteriaMap[$kriteria->id_kriteria] = $kriteria->subKriteria->map(function ($item) {
                    return [
                        'nilai' => $item->nilai,
                        'nama'  => $item->nama_subkriteria,
                    ];
                });
            }
        }

        $penilaianMatrix = [];
        foreach ($alternatifData as $alternatif) {
            foreach ($alternatif->penilaian as $penilaian) {
                $penilaianMatrix[$alternatif->id_alternatif][$penilaian->id_kriteria] = $penilaian->nilai;
            }
        }

        return view('pages.admin.spk.penilaian.index', [
            'idKeputusan'    => $this->idKeputusan,
            'keputusan'      => $this->keputusan,
            'kriteriaList'   => $kriteriaList,
            'alternatifData' => $alternatifData,
            'penilaianMatrix' => $penilaianMatrix,
            'subKriteriaMap' => $subKriteriaMap,
            'pageTitle'      => 'Manajemen Matriks Penilaian',
        ]);
    }

    public function store(Request $request, SpkKeputusan $keputusan)
    {
        return $this->processSave($request, 'menyimpan');
    }

    public function update(Request $request, SpkKeputusan $keputusan)
    {
        return $this->processSave($request, 'memperbarui');
    }

    private function processSave(Request $request, $actionVerb)
    {
        $request->validate([
            'nilai_penilaian'      => 'required|array',
            'nilai_penilaian.*.*'  => 'required|numeric',
        ], [
            'nilai_penilaian.required'     => 'Setidaknya satu nilai penilaian harus diisi.',
            'nilai_penilaian.*.*.required' => 'Semua nilai penilaian harus diisi.',
            'nilai_penilaian.*.*.numeric'  => 'Nilai penilaian harus berupa angka.',
        ]);

        $updatesCount = 0;

        foreach ($request->nilai_penilaian as $idAlternatif => $penilaianPerAlternatif) {
            foreach ($penilaianPerAlternatif as $idKriteria => $nilai) {
                Penilaian::updateOrCreate(
                    [
                        'id_alternatif' => $idAlternatif,
                        'id_kriteria'   => $idKriteria,
                    ],
                    ['nilai' => (float) $nilai]
                );
                $updatesCount++;
            }
        }

        return redirect()->back()->with('success', "Berhasil {$actionVerb} {$updatesCount} data penilaian.");
    }

    public function syncScores(SpkKeputusan $keputusan)
    {
        $kriteriaList  = Kriteria::where('id_keputusan', $this->idKeputusan)->get();
        $alternatifList = Alternatif::where('id_keputusan', $this->idKeputusan)->with('mahasiswa')->get();
        $countUpdated  = 0;

        foreach ($alternatifList as $alternatif) {
            $mahasiswa = $alternatif->mahasiswa;

            if (!$mahasiswa) {
                if ($alternatif->id_mahasiswa) {
                    $mahasiswa = \App\Models\DataMahasiswa::find($alternatif->id_mahasiswa);
                }
                if (!$mahasiswa) continue;
            }

            $prestasiValid = $mahasiswa->prestasi()->where('status_validasi', 'disetujui')->get();

            foreach ($kriteriaList as $kriteria) {
                $source      = $kriteria->sumber_data;
                $kriteriaName = strtolower($kriteria->nama_kriteria);
                $value       = 0;
                $shouldSync  = false;

                if ($source === 'Mahasiswa') {
                    $attr = $kriteria->atribut_sumber;

                    if (str_contains($kriteriaName, 'ipk')) {
                        $value      = $mahasiswa->ipk ?? 0;
                        $shouldSync = true;
                    } elseif ($attr && isset($mahasiswa->$attr)) {
                        $value      = $mahasiswa->$attr;
                        $shouldSync = true;
                    }
                } elseif ($source === 'Prestasi') {
                    if (str_contains($kriteriaName, 'tingkat') || str_contains($kriteriaName, 'level')) {
                        $value = \App\Services\SpkCalculator::calculateTingkatScore($prestasiValid, $kriteria);
                    } elseif (str_contains($kriteriaName, 'juara') || str_contains($kriteriaName, 'rank') || str_contains($kriteriaName, 'medali')) {
                        $value = \App\Services\SpkCalculator::calculateJuaraScore($prestasiValid, $kriteria);
                    } elseif (str_contains($kriteriaName, 'jenis')) {
                        $value = \App\Services\SpkCalculator::calculateJenisScore($prestasiValid, $kriteria);
                    } else {
                        $value = $prestasiValid->count();
                    }
                    $shouldSync = true;
                } elseif ($source === 'Sanksi') {
                    $value      = $mahasiswa->sanksi()->count();
                    $shouldSync = true;
                }

                if ($shouldSync) {
                    Penilaian::updateOrCreate(
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