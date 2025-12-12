<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Spk\KeputusanDetailController; 
use App\Models\spkkeputusan; 
use App\Models\kriteria;
use App\Models\penilaian;
use App\Models\subkriteria;
use App\Models\alternatif;

/**
 * Class KriteriaController
 * 
 * Controller ini bertanggung jawab untuk mengelola operasi CRUD untuk entitas Kriteria.
 * Setiap Kriteria terikat pada satu Keputusan SPK.
 * 
 * Controller ini mewarisi KeputusanDetailController untuk memastikan validasi
 * dan akses ke data Keputusan induk ($this->keputusan).
 * 
 * @package App\Http\Controllers\Spk
 */
class KriteriaController extends KeputusanDetailController
{
    /**
     * Constructor.
     * 
     * Memanggil constructor parent untuk memuat data Keputusan.
     * Ini penting agar $this->idKeputusan tersedia untuk query database selanjutnya.
     * 
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        // Memastikan Keputusan (parent) dimuat sebelum method lain dijalankan
        parent::__construct($request);
    }
    
    /**
     * Menampilkan daftar Kriteria untuk Keputusan saat ini.
     * 
     * Mengambil data kriteria beserta relasi sub-kriteria (eager loading)
     * untuk meminimalkan query database (N+1 problem) di view.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Data Keputusan sudah tersedia via $this->keputusan
        $kriteria = kriteria::where('id_keputusan', $this->idKeputusan)
                             ->with('subKriteria') 
                             ->get();

        // Peta kolom yang ramah pengguna (Shared logic)
        $columns = $this->getReadableLabels();

        return view('pages.admin.spk.kriteria.index', [
            'idKeputusan' => $this->idKeputusan,
            'keputusan' => $this->keputusan, // Digunakan untuk navigasi/breadcrumb
            'kriteriaData' => $kriteria,
            'pageTitle' => 'Manajemen Kriteria',
            'columnMap' => $columns
        ]);
    }

    /**
     * Menampilkan form untuk menambahkan Kriteria baru.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Peta kolom yang ramah pengguna
        $columns = $this->getReadableLabels();

        // Ensure we fetch generic columns if map is missing (fallback)
        foreach (['Prestasi', 'Sanksi', 'Pengaduan', 'Mahasiswa'] as $model) {
             if (!isset($columns[$model])) {
                $rawCols = \Illuminate\Support\Facades\Schema::getColumnListing(strtolower($model));
                $columns[$model] = array_combine($rawCols, $rawCols);
             }
        }

        // Data Keputusan sudah dimuat di constructor parent
        return view('pages.admin.spk.kriteria.create', [ 
            'keputusan' => $this->keputusan,
            'idKeputusan' => $this->idKeputusan,
            'pageTitle' => 'Tambah Kriteria Baru',
            'tableColumns' => $columns
        ]);
    }

    /**
     * Menyimpan Kriteria baru ke database.
     * 
     * Selain menyimpan data kriteria, method ini juga secara otomatis
     * menginisiasi record Penilaian (dengan nilai 0) untuk semua Alternatif yang sudah ada.
     * Hal ini menjaga konsistensi matriks penilaian.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi Input.
        // Rule::unique digunakan dengan klausa 'where' untuk memastikan Kode Kriteria
        // hanya unik DALAM SATU KEPUTUSAN, bukan di seluruh tabel.
        $validated = $request->validate([
            'kode_kriteria' => [
                'required',
                'string',
                'max:10',
                \Illuminate\Validation\Rule::unique('kriteria')->where(function ($query) {
                    return $query->where('id_keputusan', $this->idKeputusan);
                }),
            ],
            'nama_kriteria' => 'required|string|max:255',
            'jenis_kriteria' => 'required|in:Benefit,Cost',
            'bobot_kriteria' => 'required|numeric|between:0,1',
            'sumber_data' => 'required|in:Manual,Prestasi,Sanksi,Pengaduan,Mahasiswa',
        ], [
            'kode_kriteria.unique' => 'Kode kriteria sudah digunakan dalam keputusan ini.',
            'kode_kriteria.required' => 'Kode kriteria wajib diisi.',
            'kode_kriteria.max' => 'Kode kriteria maksimal 10 karakter.',
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'jenis_kriteria.required' => 'Jenis kriteria wajib dipilih.',
            'bobot_kriteria.required' => 'Bobot kriteria wajib diisi.',
            'bobot_kriteria.numeric' => 'Bobot kriteria harus berupa angka.',
            'bobot_kriteria.between' => 'Bobot kriteria harus antara 0 dan 1.',
            'sumber_data.required' => 'Sumber data wajib dipilih.',
            'sumber_data.in' => 'Sumber data tidak valid.',
        ]);

        // 2. Buat Kriteria baru
        $kriteriaBaru = kriteria::create([
            'id_keputusan' => $this->idKeputusan,
            'kode_kriteria' => $validated['kode_kriteria'],
            'nama_kriteria' => $validated['nama_kriteria'],
            'jenis_kriteria' => $validated['jenis_kriteria'],
            'bobot_kriteria' => $validated['bobot_kriteria'],
            'sumber_data' => $validated['sumber_data'],
            'atribut_sumber' => $request->input('atribut_sumber'), // Optional, bisa null
        ]);
        
        // 3. Generate Sub Kriteria Otomatis (jika applicable)
        $this->generateDefaultSubKriteria($kriteriaBaru);

        // 4. Inisiasi Penilaian Otomatis:
        // Saat kriteria baru ditambahkan, semua alternatif yang sudah ada harus memiliki
        // entry penilaian untuk kriteria ini (default 0).
        $alternatifList = alternatif::where('id_keputusan', $this->idKeputusan)->get();
        
        foreach ($alternatifList as $alternatif) {
            // Memastikan setiap alternatif memiliki nilai default untuk kriteria baru ini.
            penilaian::create([
                'id_alternatif' => $alternatif->id_alternatif,
                'id_kriteria' => $kriteriaBaru->id_kriteria,
                'nilai' => 0, // Nilai default 0, menunggu input user atau auto-fetch
            ]);
        }

        // Resolve readable name for success message
        $namaDisplay = $validated['nama_kriteria'];
        $labels = $this->getReadableLabels();
        if ($validated['sumber_data'] !== 'Manual' && isset($labels[$validated['sumber_data']][$namaDisplay])) {
            $namaDisplay = $labels[$validated['sumber_data']][$namaDisplay] . " (" . $validated['sumber_data'] . ")";
        }

        return redirect()->route('admin.spk.kriteria.index', $this->idKeputusan)
                         ->with('success', 'Kriteria "' . $namaDisplay . '" berhasil ditambahkan, dan penilaian alternatif sudah diinisiasi.');
    }
    
    /**
     * Menampilkan form edit untuk Kriteria tertentu.
     * 
     * @param mixed $idKeputusan Parameter route (diabaikan, pakai $this->idKeputusan)
     * @param int $idKriteria ID Kriteria yang akan diedit
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function edit($idKeputusan, $idKriteria)
    {
        // Cari kriteria berdasarkan ID Kriteria dan pastikan terikat dengan ID Keputusan saat ini
        $kriteria = kriteria::where('id_keputusan', $this->idKeputusan)
                            ->where('id_kriteria', $idKriteria)
                            ->firstOrFail();
        
        // Peta kolom yang ramah pengguna
        $columns = $this->getReadableLabels();

        // Ensure we fetch generic columns if map is missing (fallback)
        foreach (['Prestasi', 'Sanksi', 'Pengaduan', 'Mahasiswa'] as $model) {
             if (!isset($columns[$model])) {
                $rawCols = \Illuminate\Support\Facades\Schema::getColumnListing(strtolower($model));
                $columns[$model] = array_combine($rawCols, $rawCols);
             }
        }

        return view('pages.admin.spk.kriteria.edit', [
            'keputusan' => $this->keputusan,
            'kriteria' => $kriteria,
            'pageTitle' => 'Edit Kriteria',
             'tableColumns' => $columns
        ]);
    }

    /**
     * Memperbarui data Kriteria yang sudah ada.
     * 
     * @param Request $request
     * @param mixed $idKeputusan
     * @param int $idKriteria
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(Request $request, $idKeputusan, $idKriteria)
    {
        // 1. Ambil Kriteria yang akan diupdate
        $kriteria = kriteria::where('id_keputusan', $this->idKeputusan)
                            ->where('id_kriteria', $idKriteria)
                            ->firstOrFail();
        
        // 2. Validasi Input.
        // Menggunakan ignore() pada Rule::unique agar kode kriteria saat ini tidak dianggap duplikat.
        $validated = $request->validate([
            'kode_kriteria' => [
                'required',
                'string',
                'max:10',
                \Illuminate\Validation\Rule::unique('kriteria')->ignore($idKriteria, 'id_kriteria')->where(function ($query) {
                    return $query->where('id_keputusan', $this->idKeputusan);
                }),
            ],
            'nama_kriteria' => 'required|string|max:255',
            'jenis_kriteria' => 'required|in:Benefit,Cost',
            'bobot_kriteria' => 'required|numeric|between:0,1',
            'sumber_data' => 'required|in:Manual,Prestasi,Sanksi,Pengaduan,Mahasiswa',
        ], [
            'kode_kriteria.unique' => 'Kode kriteria sudah digunakan dalam keputusan ini.',
            'kode_kriteria.required' => 'Kode kriteria wajib diisi.',
            'kode_kriteria.max' => 'Kode kriteria maksimal 10 karakter.',
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'jenis_kriteria.required' => 'Jenis kriteria wajib dipilih.',
            'bobot_kriteria.required' => 'Bobot kriteria wajib diisi.',
            'bobot_kriteria.numeric' => 'Bobot kriteria harus berupa angka.',
            'bobot_kriteria.between' => 'Bobot kriteria harus antara 0 dan 1.',
            'sumber_data.required' => 'Sumber data wajib dipilih.',
            'sumber_data.in' => 'Sumber data tidak valid.',
        ]);
        
        // 3. Update data
        $dataToUpdate = $validated;
        $dataToUpdate['atribut_sumber'] = $request->input('atribut_sumber');
        
        $kriteria->update($dataToUpdate);

        // 4. Regenerate Sub Kriteria Otomatis (jika applicable)
        $this->generateDefaultSubKriteria($kriteria);

        // Resolve readable name for success message
        $namaDisplay = $kriteria->nama_kriteria;
        $labels = $this->getReadableLabels();
        if ($kriteria->sumber_data !== 'Manual' && isset($labels[$kriteria->sumber_data][$namaDisplay])) {
             $namaDisplay = $labels[$kriteria->sumber_data][$namaDisplay] . " (" . $kriteria->sumber_data . ")";
        }

        return redirect()->route('admin.spk.kriteria.index', $this->idKeputusan)
                         ->with('success', 'Kriteria "' . $namaDisplay . '" berhasil diperbarui.');
    }

    /**
     * Menghapus Kriteria beserta data turunannya.
     * 
     * Data yang dihapus:
     * 1. Penilaian yang terkait dengan kriteria ini.
     * 2. Sub Kriteria yang terkait dengan kriteria ini.
     * 3. Kriteria itu sendiri.
     * 
     * @param mixed $idKeputusan
     * @param int $idKriteria
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($idKeputusan, $idKriteria)
    {
        // 1. Ambil Kriteria yang akan dihapus
        $kriteria = kriteria::where('id_keputusan', $this->idKeputusan)
                            ->where('id_kriteria', $idKriteria)
                            ->firstOrFail();

        // 2. Hapus data anak (Subkriteria dan Penilaian)
        penilaian::where('id_kriteria', $idKriteria)->delete();
        subkriteria::where('id_kriteria', $idKriteria)->delete(); 
        
        // 3. Hapus Kriteria utama
        $nama = $kriteria->nama_kriteria;
        
        // Resolve readable name if dynamic source
        $labels = $this->getReadableLabels();
        if ($kriteria->sumber_data !== 'Manual' && isset($labels[$kriteria->sumber_data][$nama])) {
            $nama = $labels[$kriteria->sumber_data][$nama] . " (" . $kriteria->sumber_data . ")";
        }

        $kriteria->delete();

        return redirect()->route('admin.spk.kriteria.index', $this->idKeputusan)
                         ->with('success', 'Kriteria "' . $nama . '" berhasil dihapus.');
    }
    

    use \App\Traits\HasCriteriaLabels;

    /**
     * Helper: Generate default sub-criteria based on source/attribute.
     */
    private function generateDefaultSubKriteria($kriteria)
    {
        // 1. Define mappings
        $mappings = [
            'Prestasi' => [
                'tingkat_prestasi' => [ // Column Name
                    ['nama' => 'Internasional', 'nilai' => 4],
                    ['nama' => 'Nasional', 'nilai' => 3],
                    ['nama' => 'Provinsi', 'nilai' => 2],
                    ['nama' => 'Lokal', 'nilai' => 1],
                ],
                // ... (rest of logic same as before)
                'jenis_prestasi' => [
                    ['nama' => 'Akademik', 'nilai' => 1],
                    ['nama' => 'Non-Akademik', 'nilai' => 1],
                ],
            ],
            'Sanksi' => [
                'jenis_sanksi' => [
                    ['nama' => 'Berat', 'nilai' => 3],
                    ['nama' => 'Sedang', 'nilai' => 2],
                    ['nama' => 'Ringan', 'nilai' => 1],
                ],
            ],
            'Pengaduan' => [
                'status' => [
                    ['nama' => 'Selesai', 'nilai' => 3],
                    ['nama' => 'Diproses', 'nilai' => 2],
                    ['nama' => 'Terkirim', 'nilai' => 1],
                    ['nama' => 'Ditolak', 'nilai' => 1], // Or 0
                ],
            ],
        ];

        $source = $kriteria->sumber_data;
        $column = $kriteria->nama_kriteria;

        // 2. Check match
        if ($source !== 'Manual' && isset($mappings[$source][$column])) {
            // Delete existing sub-criteria first (for update scenario)
            subkriteria::where('id_kriteria', $kriteria->id_kriteria)->delete();

            // Insert new ones
            foreach ($mappings[$source][$column] as $item) {
                subkriteria::create([
                    'id_kriteria' => $kriteria->id_kriteria,
                    'id_keputusan' => $kriteria->id_keputusan,
                    'nama_subkriteria' => $item['nama'],
                    'nilai' => $item['nilai'],
                ]);
            }
        }
    }

    public function subkriteriaIndex($idKriteria)
    {
        // Data Keputusan sudah tersedia via $this->keputusan
        $kriteria = kriteria::where('id_keputusan', $this->idKeputusan)->where('id_kriteria', $idKriteria)->firstOrFail();
        
        // Ambil data subkriteria terkait
        $subKriteriaData = subkriteria::where('id_kriteria', $idKriteria)->get();

        return view('pages.admin.spk.subkriteria.index', [
            'keputusan' => $this->keputusan,
            'kriteria' => $kriteria,
            'subKriteriaData' => $subKriteriaData,
            'pageTitle' => 'Manajemen Sub Kriteria: ' . $kriteria->kode_kriteria,
        ]);
    }
}