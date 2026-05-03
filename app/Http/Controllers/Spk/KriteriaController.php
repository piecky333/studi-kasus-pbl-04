<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Spk\KeputusanDetailController;
use App\Models\SpkKeputusan;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use App\Models\Alternatif;

class KriteriaController extends KeputusanDetailController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index()
    {
        $kriteria = Kriteria::where('id_keputusan', $this->idKeputusan)
                             ->with('subKriteria')
                             ->get();

        $columns = $this->getReadableLabels();

        return view('pages.admin.spk.kriteria.index', [
            'idKeputusan' => $this->idKeputusan,
            'keputusan'   => $this->keputusan,
            'kriteriaData' => $kriteria,
            'pageTitle'   => 'Manajemen Kriteria',
            'columnMap'   => $columns,
        ]);
    }

    public function create()
    {
        $columns = $this->getReadableLabels();

        foreach (['Prestasi', 'Sanksi', 'Pengaduan', 'Mahasiswa'] as $model) {
            if (!isset($columns[$model])) {
                $rawCols          = \Illuminate\Support\Facades\Schema::getColumnListing(strtolower($model));
                $columns[$model]  = array_combine($rawCols, $rawCols);
            }
        }

        return view('pages.admin.spk.kriteria.create', [
            'keputusan'    => $this->keputusan,
            'idKeputusan'  => $this->idKeputusan,
            'pageTitle'    => 'Tambah Kriteria Baru',
            'tableColumns' => $columns,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_kriteria' => [
                'required',
                'string',
                'max:10',
                \Illuminate\Validation\Rule::unique('kriteria')->where(function ($query) {
                    return $query->where('id_keputusan', $this->idKeputusan);
                }),
            ],
            'nama_kriteria' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('kriteria')->where(function ($query) {
                    return $query->where('id_keputusan', $this->idKeputusan);
                }),
            ],
            'jenis_kriteria' => 'required|in:Benefit,Cost',
            'bobot_kriteria' => 'required|numeric|between:0,1',
            'sumber_data'    => 'required|in:Manual,Prestasi,Sanksi,Pengaduan,Mahasiswa',
        ], [
            'kode_kriteria.unique'   => 'Kode kriteria sudah digunakan dalam keputusan ini.',
            'kode_kriteria.required' => 'Kode kriteria wajib diisi.',
            'kode_kriteria.max'      => 'Kode kriteria maksimal 10 karakter.',
            'nama_kriteria.unique'   => 'Nama kriteria sudah digunakan dalam keputusan ini.',
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'jenis_kriteria.required' => 'Jenis kriteria wajib dipilih.',
            'bobot_kriteria.required' => 'Bobot kriteria wajib diisi.',
            'bobot_kriteria.numeric'  => 'Bobot kriteria harus berupa angka.',
            'bobot_kriteria.between'  => 'Bobot kriteria harus antara 0 dan 1.',
            'sumber_data.required'   => 'Sumber data wajib dipilih.',
            'sumber_data.in'         => 'Sumber data tidak valid.',
        ]);

        $kriteriaBaru = Kriteria::create([
            'id_keputusan'  => $this->idKeputusan,
            'kode_kriteria' => $validated['kode_kriteria'],
            'nama_kriteria' => $validated['nama_kriteria'],
            'jenis_kriteria' => $validated['jenis_kriteria'],
            'bobot_kriteria' => $validated['bobot_kriteria'],
            'sumber_data'   => $validated['sumber_data'],
            'atribut_sumber' => $request->input('atribut_sumber'),
        ]);

        $this->generateDefaultSubKriteria($kriteriaBaru);

        $alternatifList = Alternatif::where('id_keputusan', $this->idKeputusan)->get();

        foreach ($alternatifList as $alternatif) {
            Penilaian::create([
                'id_alternatif' => $alternatif->id_alternatif,
                'id_kriteria'   => $kriteriaBaru->id_kriteria,
                'nilai'         => 0,
            ]);
        }

        $namaDisplay = $validated['nama_kriteria'];
        $labels      = $this->getReadableLabels();
        if ($validated['sumber_data'] !== 'Manual' && isset($labels[$validated['sumber_data']][$namaDisplay])) {
            $namaDisplay = $labels[$validated['sumber_data']][$namaDisplay] . " (" . $validated['sumber_data'] . ")";
        }

        return redirect()->route('admin.spk.kriteria.index', $this->keputusan)
                         ->with('success', 'Kriteria "' . $namaDisplay . '" berhasil ditambahkan, dan penilaian alternatif sudah diinisiasi.');
    }

    public function edit(SpkKeputusan $keputusan, Kriteria $kriteria)
    {
        $columns = $this->getReadableLabels();

        foreach (['Prestasi', 'Sanksi', 'Pengaduan', 'Mahasiswa'] as $model) {
            if (!isset($columns[$model])) {
                $rawCols         = \Illuminate\Support\Facades\Schema::getColumnListing(strtolower($model));
                $columns[$model] = array_combine($rawCols, $rawCols);
            }
        }

        return view('pages.admin.spk.kriteria.edit', [
            'keputusan'    => $this->keputusan,
            'kriteria'     => $kriteria,
            'pageTitle'    => 'Edit Kriteria',
            'tableColumns' => $columns,
        ]);
    }

    public function update(Request $request, SpkKeputusan $keputusan, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'kode_kriteria' => [
                'required',
                'string',
                'max:10',
                \Illuminate\Validation\Rule::unique('kriteria')->ignore($kriteria->id_kriteria, 'id_kriteria')->where(function ($query) {
                    return $query->where('id_keputusan', $this->idKeputusan);
                }),
            ],
            'nama_kriteria' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('kriteria')->where(function ($query) {
                    return $query->where('id_keputusan', $this->idKeputusan);
                })->ignore($kriteria->id_kriteria, 'id_kriteria'),
            ],
            'jenis_kriteria' => 'required|in:Benefit,Cost',
            'bobot_kriteria' => 'required|numeric|between:0,1',
            'sumber_data'    => 'required|in:Manual,Prestasi,Sanksi,Pengaduan,Mahasiswa',
        ], [
            'kode_kriteria.unique'   => 'Kode kriteria sudah digunakan dalam keputusan ini.',
            'kode_kriteria.required' => 'Kode kriteria wajib diisi.',
            'kode_kriteria.max'      => 'Kode kriteria maksimal 10 karakter.',
            'nama_kriteria.unique'   => 'Nama kriteria sudah digunakan dalam keputusan ini.',
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'jenis_kriteria.required' => 'Jenis kriteria wajib dipilih.',
            'bobot_kriteria.required' => 'Bobot kriteria wajib diisi.',
            'bobot_kriteria.numeric'  => 'Bobot kriteria harus berupa angka.',
            'bobot_kriteria.between'  => 'Bobot kriteria harus antara 0 dan 1.',
            'sumber_data.required'   => 'Sumber data wajib dipilih.',
            'sumber_data.in'         => 'Sumber data tidak valid.',
        ]);

        $dataToUpdate                  = $validated;
        $dataToUpdate['atribut_sumber'] = $request->input('atribut_sumber');

        $kriteria->update($dataToUpdate);

        $this->generateDefaultSubKriteria($kriteria);

        $namaDisplay = $kriteria->nama_kriteria;
        $labels      = $this->getReadableLabels();
        if ($kriteria->sumber_data !== 'Manual' && isset($labels[$kriteria->sumber_data][$namaDisplay])) {
            $namaDisplay = $labels[$kriteria->sumber_data][$namaDisplay] . " (" . $kriteria->sumber_data . ")";
        }

        return redirect()->route('admin.spk.kriteria.index', $this->keputusan)
                         ->with('success', 'Kriteria "' . $namaDisplay . '" berhasil diperbarui.');
    }

    public function destroy(SpkKeputusan $keputusan, Kriteria $kriteria)
    {
        Penilaian::where('id_kriteria', $kriteria->id_kriteria)->delete();
        SubKriteria::where('id_kriteria', $kriteria->id_kriteria)->delete();

        $nama   = $kriteria->nama_kriteria;
        $labels = $this->getReadableLabels();
        if ($kriteria->sumber_data !== 'Manual' && isset($labels[$kriteria->sumber_data][$nama])) {
            $nama = $labels[$kriteria->sumber_data][$nama] . " (" . $kriteria->sumber_data . ")";
        }

        $kriteria->delete();

        return redirect()->route('admin.spk.kriteria.index', $this->keputusan)
                         ->with('success', 'Kriteria "' . $nama . '" berhasil dihapus.');
    }

    use \App\Traits\HasCriteriaLabels;

    private function generateDefaultSubKriteria($kriteria)
    {
        $mappings = [
            'Prestasi' => [
                'tingkat_prestasi' => [
                    ['nama' => 'Internasional', 'nilai' => 4],
                    ['nama' => 'Nasional',      'nilai' => 3],
                    ['nama' => 'Provinsi',      'nilai' => 2],
                    ['nama' => 'Lokal',         'nilai' => 1],
                ],
                'jenis_prestasi' => [
                    ['nama' => 'Akademik',     'nilai' => 1],
                    ['nama' => 'Non-Akademik', 'nilai' => 1],
                ],
            ],
            'Sanksi' => [
                'jenis_sanksi' => [
                    ['nama' => 'Berat',  'nilai' => 3],
                    ['nama' => 'Sedang', 'nilai' => 2],
                    ['nama' => 'Ringan', 'nilai' => 1],
                ],
            ],
            'Pengaduan' => [
                'status' => [
                    ['nama' => 'Selesai',  'nilai' => 3],
                    ['nama' => 'Diproses', 'nilai' => 2],
                    ['nama' => 'Terkirim', 'nilai' => 1],
                    ['nama' => 'Ditolak',  'nilai' => 1],
                ],
            ],
        ];

        $source = $kriteria->sumber_data;
        $column = $kriteria->nama_kriteria;

        if ($source !== 'Manual' && isset($mappings[$source][$column])) {
            SubKriteria::where('id_kriteria', $kriteria->id_kriteria)->delete();

            foreach ($mappings[$source][$column] as $item) {
                SubKriteria::create([
                    'id_kriteria'    => $kriteria->id_kriteria,
                    'id_keputusan'   => $kriteria->id_keputusan,
                    'nama_subkriteria' => $item['nama'],
                    'nilai'          => $item['nilai'],
                ]);
            }
        }
    }

    public function subkriteriaIndex($idKriteria)
    {
        $kriteria = Kriteria::where('id_keputusan', $this->idKeputusan)
                            ->where('id_kriteria', $idKriteria)
                            ->firstOrFail();

        $subKriteriaData = SubKriteria::where('id_kriteria', $idKriteria)->get();

        return view('pages.admin.spk.subkriteria.index', [
            'keputusan'      => $this->keputusan,
            'kriteria'       => $kriteria,
            'subKriteriaData' => $subKriteriaData,
            'pageTitle'      => 'Manajemen Sub Kriteria: ' . $kriteria->kode_kriteria,
        ]);
    }
}