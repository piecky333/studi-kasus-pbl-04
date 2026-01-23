<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Models\SubKriteria;
use App\Models\Kriteria;
use App\Http\Controllers\Spk\KeputusanDetailController; 
use Illuminate\Validation\ValidationException; 

/**
 * Class SubKriteriaController
 * 
 * Controller ini bertanggung jawab untuk mengelola operasi CRUD Sub Kriteria.
 * 
 * Keamanan dan Integritas Data:
 * Controller ini menerapkan "Scoped Access" yang ketat. Setiap operasi (Edit/Delete)
 * memverifikasi bahwa Sub Kriteria yang diakses benar-benar milik Kriteria yang sedang aktif,
 * dan Kriteria tersebut milik Keputusan yang sedang aktif.
 * 
 * @package App\Http\Controllers\Spk
 */
class SubKriteriaController extends KeputusanDetailController
{
    /** @var int ID Kriteria Induk dari URL. */
    protected $idKriteria;
    /** @var Kriteria Model Kriteria Induk yang sudah dimuat. */
    protected $kriteria;

    /**
     * Constructor.
     * 
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        // Memuat $this->idKeputusan dari parent controller (KeputusanDetailController)
        parent::__construct($request);
    }

    /**
     * Helper Method: Memuat dan Memvalidasi Kriteria.
     * 
     * Method ini memastikan bahwa ID Kriteria yang diterima dari URL valid
     * dan benar-benar milik Keputusan saat ini ($this->idKeputusan).
     * Jika tidak valid, akan melempar ModelNotFoundException (404).
     * 
     * @param int|string $idKriteria
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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
     * Menampilkan daftar Sub Kriteria untuk Kriteria tertentu.
     * 
     * @param mixed $idKeputusan
     * @param mixed $idKriteria
     * @return \Illuminate\View\View
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
     * Menampilkan form untuk membuat Sub Kriteria baru.
     * 
     * @param mixed $idKeputusan
     * @param mixed $idKriteria
     * @return \Illuminate\View\View
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
     * Menyimpan Sub Kriteria baru ke database.
     * 
     * @param Request $request
     * @param mixed $idKeputusan
     * @param mixed $idKriteria
     * @return \Illuminate\Http\RedirectResponse
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
        ])->with('success', 'SubKriteria berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit untuk Sub Kriteria.
     * 
     * @param mixed $idKeputusan
     * @param mixed $idKriteria
     * @param int|string $subkriteriumId ID Sub Kriteria yang akan diedit
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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
     * Memperbarui data Sub Kriteria.
     * 
     * @param Request $request
     * @param mixed $idKeputusan
     * @param mixed $idKriteria
     * @param int|string $subkriteriumId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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
        ])->with('success', 'SubKriteria berhasil diperbarui.');
    }

    /**
     * Menghapus Sub Kriteria.
     * 
     * @param mixed $idKeputusan
     * @param mixed $idKriteria
     * @param int|string $subkriteriumId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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
        ])->with('success', 'SubKriteria berhasil dihapus.');
    }
}