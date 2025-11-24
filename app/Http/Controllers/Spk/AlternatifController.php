<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Spk\KeputusanDetailController;
use App\Models\alternatif;
use App\Models\kriteria;
use App\Models\penilaian;
use App\Models\hasilakhir;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // Import untuk validasi unik yang diabaikan

/**
 * Controller ini mengelola CRUD untuk entitas Alternatif di bawah sebuah Keputusan SPK.
 * Mewarisi KeputusanDetailController untuk memastikan konteks Keputusan sudah dimuat.
 */
class AlternatifController extends KeputusanDetailController
{
    /**
     * Constructor untuk memuat Keputusan induk.
     */
    public function __construct(Request $request)
    {
        // Memastikan Keputusan (parent) dimuat sebelum method lain dijalankan
        parent::__construct($request);
    }

    /**
     * Menampilkan daftar Alternatif untuk Keputusan yang spesifik (Tab Alternatif).
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $alternatifList = alternatif::where('id_keputusan', $this->idKeputusan)
            ->orderBy('nama_alternatif', 'asc')
            ->get();
        
        return view('pages.admin.spk.alternatif.index', [
            'idKeputusan' => $this->idKeputusan, 
            'keputusan' => $this->keputusan, 
            'alternatifList' => $alternatifList,
            'pageTitle' => 'Manajemen Alternatif'
        ]);
    }

    /**
     * Menampilkan form untuk membuat Alternatif baru.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.admin.spk.alternatif.create', [
            'idKeputusan' => $this->idKeputusan,
            'keputusan' => $this->keputusan,
            'pageTitle' => 'Tambah Alternatif Baru'
        ]);
    }

    /**
     * Menyimpan Alternatif baru ke database dan menginisiasi penilaian untuk kriteria yang sudah ada.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_alternatif' => 'required|string|max:255',
            // Tambahkan validasi untuk kolom baru
            'id_mahasiswa' => [
                'nullable', 
                'string', 
                'max:50',
                // Pastikan id_mahasiswa unik hanya dalam konteks keputusan ini
                Rule::unique('alternatif')->where(function ($query) {
                    return $query->where('id_keputusan', $this->idKeputusan);
                }),
            ],
            'keterangan' => 'nullable|string|max:1000',
        ]);

        // Gabungkan data Keputusan ID ke dalam array validasi
        $dataToStore = array_merge($validated, ['id_keputusan' => $this->idKeputusan]);

        // 1. Buat Alternatif baru
        $alternatifBaru = alternatif::create($dataToStore);
        
        // 2. Inisiasi Penilaian: Tambahkan entri Penilaian dengan nilai default (misal 0) 
        $kriteriaList = kriteria::where('id_keputusan', $this->idKeputusan)->get();
        
        foreach ($kriteriaList as $kriteria) {
            penilaian::create([
                'id_alternatif' => $alternatifBaru->id_alternatif,
                'id_kriteria' => $kriteria->id_kriteria,
                'nilai' => 0, 
            ]);
        }

        return redirect()->route('admin.spk.alternatif.index', $this->idKeputusan)
                         ->with('success', 'Alternatif "' . $validated['nama_alternatif'] . '" berhasil ditambahkan, dan penilaian inisial sudah dibuat.');
    }

    /**
     * Menampilkan form edit Alternatif.
     * @param mixed $idKeputusan // Ditangkap dari route, tapi kita pakai $this->idKeputusan
     * @param int $idAlternatif
     * @return \Illuminate\View\View
     */
    public function edit($idKeputusan, $idAlternatif)
    {
        $alternatif = alternatif::where('id_keputusan', $this->idKeputusan)
                                     ->findOrFail($idAlternatif);

        return view('pages.admin.spk.alternatif.edit', [
            'idKeputusan' => $this->idKeputusan,
            'keputusan' => $this->keputusan,
            'alternatif' => $alternatif,
            'pageTitle' => 'Edit Alternatif'
        ]);
    }

    /**
     * Memperbarui Alternatif yang sudah ada.
     * @param Request $request
     * @param mixed $idKeputusan // Ditangkap dari route, tapi kita pakai $this->idKeputusan
     * @param int $idAlternatif
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $idKeputusan, $idAlternatif)
    {
        $alternatif = alternatif::where('id_keputusan', $this->idKeputusan)
                                     ->findOrFail($idAlternatif);
        
        // 1. Validasi Input, termasuk kolom baru dan memastikan ID Mahasiswa unik (kecuali untuk dirinya sendiri)
        $validated = $request->validate([
            'nama_alternatif' => 'required|string|max:255',
            'id_mahasiswa' => [
                'nullable', 
                'string', 
                'max:50',
                // Rule untuk memastikan ID Mahasiswa unik dalam keputusan ini,
                // TAPI MENGABAIKAN data alternatif yang sedang di-edit (menggunakan id_alternatif).
                Rule::unique('alternatif')->where(function ($query) use ($alternatif) {
                    return $query->where('id_keputusan', $this->idKeputusan)
                                 ->where('id_alternatif', '!=', $alternatif->id_alternatif);
                }),
            ],
            'keterangan' => 'nullable|string|max:1000',
        ]);
        
        // 2. Update model dengan semua data yang tervalidasi
        $alternatif->update($validated);

        return redirect()->route('admin.spk.alternatif.index', $this->idKeputusan)
                         ->with('success', 'Alternatif "' . $alternatif->nama_alternatif . '" berhasil diperbarui.');
    }

    /**
     * Menghapus Alternatif dan data terkait (penilaian, hasil akhir).
     * @param mixed $idKeputusan // Ditangkap dari route, tapi kita pakai $this->idKeputusan
     * @param int $idAlternatif
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($idKeputusan, $idAlternatif)
    {
        DB::beginTransaction();
        try {
            $alternatif = alternatif::where('id_keputusan', $this->idKeputusan)
                                     ->findOrFail($idAlternatif);
            $nama = $alternatif->nama_alternatif;

            penilaian::where('id_alternatif', $idAlternatif)->delete();
            hasilakhir::where('id_alternatif', $idAlternatif)->delete(); 
            
            $alternatif->delete();

            DB::commit();

            return redirect()->route('admin.spk.alternatif.index', $this->idKeputusan)
                             ->with('success', 'Alternatif "' . $nama . '" berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.spk.alternatif.index', $this->idKeputusan)
                             ->with('error', 'Gagal menghapus alternatif: ' . $e->getMessage());
        }
    }
} 