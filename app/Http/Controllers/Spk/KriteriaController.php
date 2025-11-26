<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
// PENTING: Gunakan Base Controller yang sudah kita definisikan
use App\Http\Controllers\Spk\KeputusanDetailController; 
use App\Models\spkkeputusan; 
use App\Models\kriteria;
use App\Models\penilaian;
use App\Models\subkriteria;
use App\Models\alternatif;

/**
 * Controller ini mengelola CRUD untuk entitas Kriteria di bawah sebuah Keputusan SPK.
 * Mewarisi KeputusanDetailController memastikan model Keputusan induk sudah dimuat.
 */
class KriteriaController extends KeputusanDetailController
{
    /**
     * Karena Controller ini extend KeputusanDetailController, kita perlu menambahkan 
     * constructor untuk memanggil parent dan menghindari konflik.
     */
    public function __construct(Request $request)
    {
        // Memastikan Keputusan (parent) dimuat sebelum method lain dijalankan
        parent::__construct($request);
    }
    
    /**
     * Menampilkan daftar Kriteria dan Sub Kriteria untuk Keputusan saat ini (Tab Kriteria).
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Data Keputusan sudah tersedia via $this->keputusan
        $kriteria = kriteria::where('id_keputusan', $this->idKeputusan)
                             ->with('subKriteria') 
                             ->get();

        return view('pages.admin.spk.kriteria.index', [
            'idKeputusan' => $this->idKeputusan,
            'keputusan' => $this->keputusan, // Digunakan untuk navigasi/breadcrumb
            'kriteriaData' => $kriteria,
            'pageTitle' => 'Manajemen Kriteria'
        ]);
    }

    /**
     * Menampilkan form untuk menambah Kriteria baru.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Data Keputusan sudah dimuat di constructor parent
        return view('pages.admin.spk.kriteria.create', [ 
            'keputusan' => $this->keputusan,
            'idKeputusan' => $this->idKeputusan,
            'pageTitle' => 'Tambah Kriteria Baru'
        ]);
    }

    /**
     * Menyimpan Kriteria baru ke database dan menginisiasi penilaian untuk alternatif yang sudah ada.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi Input. Aturan unique disesuaikan dengan ID Keputusan saat ini.
        $validated = $request->validate([
            'kode_kriteria' => 'required|string|max:10|unique:kriteria,kode_kriteria,NULL,id_kriteria,id_keputusan,'.$this->idKeputusan, 
            'nama_kriteria' => 'required|string|max:255',
            'jenis_kriteria' => 'required|in:Benefit,Cost',
            // NOTE: Bobot kriteria seharusnya diatur melalui AHP, bukan input manual. 
            // Jika AHP digunakan, nilai ini harusnya nol/default. Saya biarkan validasi Anda.
            'bobot_kriteria' => 'required|numeric|between:0,1', 
            'cara_penilaian' => 'nullable|string', 
        ]);

        // 2. Buat Kriteria baru
        $kriteriaBaru = kriteria::create([
            'id_keputusan' => $this->idKeputusan,
            'kode_kriteria' => $validated['kode_kriteria'],
            'nama_kriteria' => $validated['nama_kriteria'],
            'jenis_kriteria' => $validated['jenis_kriteria'],
            'bobot_kriteria' => $validated['bobot_kriteria'],
            'cara_penilaian' => $validated['cara_penilaian'] ?? 'Input Langsung', 
        ]);
        
        // 3. Inisiasi Penilaian: Tambahkan entri Penilaian untuk semua alternatif yang sudah ada.
        $alternatifList = alternatif::where('id_keputusan', $this->idKeputusan)->get();
        
        foreach ($alternatifList as $alternatif) {
            // Memastikan setiap alternatif memiliki nilai default untuk kriteria baru ini.
            penilaian::create([
                'id_alternatif' => $alternatif->id_alternatif,
                'id_kriteria' => $kriteriaBaru->id_kriteria,
                'nilai' => 0, // Nilai default 0, menunggu input user
            ]);
        }

        return redirect()->route('admin.spk.kriteria.index', $this->idKeputusan)
                         ->with('success', 'Kriteria "' . $validated['nama_kriteria'] . '" berhasil ditambahkan, dan penilaian alternatif sudah diinisiasi.');
    }
    
    /**
     * Menampilkan form edit Kriteria.
     * @param int $idKriteria
     * @return \Illuminate\View\View
     */
    public function edit($idKeputusan, $idKriteria)
    {
        // Cari kriteria berdasarkan ID Kriteria dan pastikan terikat dengan ID Keputusan saat ini
        $kriteria = kriteria::where('id_keputusan', $this->idKeputusan)
                            ->where('id_kriteria', $idKriteria)
                            ->firstOrFail();
        
        return view('pages.admin.spk.kriteria.edit', [
            'keputusan' => $this->keputusan,
            'kriteria' => $kriteria,
            'pageTitle' => 'Edit Kriteria'
        ]);
    }

    /**
     * Memperbarui Kriteria yang sudah ada.
     * @param Request $request
     * @param int $idKriteria
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $idKeputusan, $idKriteria)
    {
        // 1. Ambil Kriteria yang akan diupdate
        $kriteria = kriteria::where('id_keputusan', $this->idKeputusan)
                            ->where('id_kriteria', $idKriteria)
                            ->firstOrFail();
        
        // 2. Validasi Input (menggunakan ID Kriteria yang dikecualikan)
        $validated = $request->validate([
            'kode_kriteria' => 'required|string|max:10|unique:kriteria,kode_kriteria,'.$idKriteria.',id_kriteria,id_keputusan,'.$this->idKeputusan,
            'nama_kriteria' => 'required|string|max:255',
            'jenis_kriteria' => 'required|in:Benefit,Cost',
            'bobot_kriteria' => 'required|numeric|between:0,1',
            'cara_penilaian' => 'nullable|string', 
        ]);
        
        // 3. Update data
        $dataToUpdate = $validated;
        if (!isset($dataToUpdate['cara_penilaian'])) {
             // Jika cara_penilaian tidak dikirim, gunakan nilai yang sudah ada
             $dataToUpdate['cara_penilaian'] = $kriteria->cara_penilaian ?? 'Input Langsung'; 
        }
        
        $kriteria->update($dataToUpdate);

        return redirect()->route('admin.spk.kriteria.index', $this->idKeputusan)
                         ->with('success', 'Kriteria "' . $kriteria->nama_kriteria . '" berhasil diperbarui.');
    }

    /**
     * Menghapus Kriteria dan data terkait (penilaian, subkriteria).
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
        $kriteria->delete();

        return redirect()->route('admin.spk.kriteria.index', $this->idKeputusan)
                         ->with('success', 'Kriteria "' . $nama . '" berhasil dihapus.');
    }
    
    /**
     * Menampilkan daftar Sub Kriteria (untuk Kriteria tertentu).
     * NOTE: Fungsi ini sekarang seharusnya dihandle oleh SubKriteriaController@index.
     * @param int $idKriteria
     * @return \Illuminate\View\View
     */
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