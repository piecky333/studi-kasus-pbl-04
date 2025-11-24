<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Models\SubKriteria;
use App\Models\Kriteria;
use App\Http\Controllers\Spk\KeputusanDetailController; 
use Illuminate\Validation\ValidationException; 

/**
 * Class SubKriteriaController
 * * Bertanggung jawab mengelola Sub Kriteria. Controller ini memastikan setiap operasi
 * (CRUD) dilakukan dalam konteks Kriteria ($this->idKriteria) dan Keputusan ($this->idKeputusan)
 * yang benar, meningkatkan keamanan melalui scoping data.
 */
class SubKriteriaController extends KeputusanDetailController
{
    /** @var int ID Kriteria Induk dari URL. */
    protected $idKriteria;
    /** @var Kriteria Model Kriteria Induk yang sudah dimuat. */
    protected $kriteria;

    /**
     * Inisialisasi.
     * Note: Kita tidak lagi memuat idKriteria di sini karena akan di-inject per method.
     */
    public function __construct(Request $request)
    {
        parent::__construct($request); // Memuat $this->idKeputusan dari parent
    }

    /**
     * Helper untuk memuat dan memvalidasi Kriteria.
     */
    private function loadKriteria($idKriteria)
    {
        $this->idKriteria = $idKriteria;
        // SECURITY SCOPE: Memastikan Kriteria yang dimuat benar-benar terikat pada Keputusan aktif.
        $this->kriteria = Kriteria::where('id_keputusan', $this->idKeputusan)
                                  ->findOrFail($this->idKriteria);
    }

    // ----------------------------------------------------------------------------------
    // CRUD Operations
    // ----------------------------------------------------------------------------------

    /**
     * Menampilkan daftar Sub Kriteria.
     */
    public function index($idKeputusan, $idKriteria)
    {
        $this->loadKriteria($idKriteria);

        // SCOPE: Hanya ambil subkriteria yang terkait dengan kriteria ini.
        $subkriteriaList = SubKriteria::where('id_kriteria', $this->idKriteria)
            ->orderBy('nilai', 'asc')
            ->get();

        $pageTitle = 'Kelola Sub Kriteria';

        return view('pages.admin.spk.kriteria.subkriteria.index', compact('pageTitle', 'subkriteriaList') + [
            'idKeputusan' => $this->idKeputusan,
            'keputusan' => $this->keputusan,
            'kriteria' => $this->kriteria,
        ]);
    }

    /**
     * Menampilkan form untuk membuat subkriteria baru.
     */
    public function create($idKeputusan, $idKriteria)
    {
        $this->loadKriteria($idKriteria);

        $pageTitle = 'Tambah Sub Kriteria Baru';

        return view('pages.admin.spk.kriteria.subkriteria.create', compact('pageTitle') + [
            'idKeputusan' => $this->idKeputusan,
            'keputusan' => $this->keputusan,
            'kriteria' => $this->kriteria,
        ]);
    }

    /**
     * Menyimpan subkriteria baru.
     */
    public function store(Request $request, $idKeputusan, $idKriteria)
    {
        $this->loadKriteria($idKriteria);

        $request->validate([
            'nama_subkriteria' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0', 
        ]);

        // DATA INTEGRITY: Menyimpan Foreign Key id_keputusan dan id_kriteria secara eksplisit.
        SubKriteria::create([
            'id_kriteria' => $this->idKriteria,
            'id_keputusan' => $this->idKeputusan, // Diperlukan jika tabel subkriteria NOT NULL
            'nama_subkriteria' => $request->nama_subkriteria,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('admin.spk.kriteria.subkriteria.index', [
            'idKeputusan' => $this->idKeputusan,
            'idKriteria' => $this->idKriteria
        ])->with('success', 'Subkriteria berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit subkriteria.
     * Menggunakan Manual Lookup untuk validasi otorisasi dan keamanan.
     *
     * @param int|string $subkriteriumId Parameter ID Sub Kriteria dari URL.
     */
    public function edit($idKeputusan, $idKriteria, int|string $subkriteriumId)
    {
        $this->loadKriteria($idKriteria);

        // MANUAL LOOKUP + SECURITY SCOPE
        $subkriterium = SubKriteria::where('id_kriteria', $this->idKriteria)
                                   ->findOrFail($subkriteriumId);

        $pageTitle = 'Edit Sub Kriteria: ' . $subkriterium->nama_subkriteria;

        return view('pages.admin.spk.kriteria.subkriteria.edit', compact('pageTitle', 'subkriterium') + [
            'idKeputusan' => $this->idKeputusan,
            'keputusan' => $this->keputusan,
            'kriteria' => $this->kriteria,
        ]);
    }

    /**
     * Memperbarui subkriteria yang ada.
     *
     * @param Request $request
     * @param int|string $subkriteriumId
     */
    public function update(Request $request, $idKeputusan, $idKriteria, int|string $subkriteriumId)
    {
        $this->loadKriteria($idKriteria);

        // MANUAL LOOKUP + SECURITY SCOPE
        $subkriterium = SubKriteria::where('id_kriteria', $this->idKriteria)
                                   ->findOrFail($subkriteriumId);

        $request->validate([
            'nama_subkriteria' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0',
        ]);

        $subkriterium->update([
            'nama_subkriteria' => $request->nama_subkriteria,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('admin.spk.kriteria.subkriteria.index', [
            'idKeputusan' => $this->idKeputusan,
            'idKriteria' => $this->idKriteria
        ])->with('success', 'Subkriteria berhasil diperbarui.');
    }

    /**
     * Menghapus subkriteria.
     * Menggunakan Manual Lookup untuk validasi otorisasi dan keamanan.
     *
     * @param int|string $subkriteriumId Parameter ID Sub Kriteria dari URL.
     */
    public function destroy($idKeputusan, $idKriteria, int|string $subkriteriumId)
    {
        $this->loadKriteria($idKriteria);

        // MANUAL LOOKUP + SECURITY SCOPE
        $subkriterium = SubKriteria::where('id_kriteria', $this->idKriteria)
                                   ->findOrFail($subkriteriumId);

        $subkriterium->delete();

        return redirect()->route('admin.spk.kriteria.subkriteria.index', [
            'idKeputusan' => $this->idKeputusan,
            'idKriteria' => $this->idKriteria
        ])->with('success', 'Subkriteria berhasil dihapus.');
    }
}