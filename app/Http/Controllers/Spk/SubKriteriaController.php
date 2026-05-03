<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Models\SubKriteria;
use App\Models\Kriteria;
use App\Models\SpkKeputusan;
use App\Http\Controllers\Spk\KeputusanDetailController; 
use Illuminate\Validation\ValidationException; 
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     * Helper Method: Memuat dan Memvalidasi Kriteria (Opsional jika menggunakan Route Model Binding).
     * 
     * @param Kriteria $kriteria
     */
    private function setKriteria(Kriteria $kriteria)
    {
        $this->kriteria = $kriteria;
        $this->idKriteria = $kriteria->id_kriteria;
    }

    // ----------------------------------------------------------------------------------
    // CRUD Operations
    // ----------------------------------------------------------------------------------

    /**
     * Menampilkan daftar Sub Kriteria untuk Kriteria tertentu.
     * 
     * @param SpkKeputusan $keputusan
     * @param Kriteria $kriteria
     * @return \Illuminate\View\View
     */
    public function index(SpkKeputusan $keputusan, Kriteria $kriteria)
    {
        $this->setKriteria($kriteria);

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
     * @param SpkKeputusan $keputusan
     * @param Kriteria $kriteria
     * @return \Illuminate\View\View
     */
    public function create(SpkKeputusan $keputusan, Kriteria $kriteria)
    {
        $this->setKriteria($kriteria);

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
     * @param SpkKeputusan $keputusan
     * @param Kriteria $kriteria
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, SpkKeputusan $keputusan, Kriteria $kriteria)
    {
        $this->setKriteria($kriteria);

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
            'keputusan' => $this->keputusan,
            'kriteria' => $this->kriteria
        ])->with('success', 'SubKriteria berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit untuk Sub Kriteria.
     * 
     * @param SpkKeputusan $keputusan
     * @param Kriteria $kriteria
     * @param SubKriteria $subkriteria
     * @return \Illuminate\View\View
     * @throws ModelNotFoundException
     */
    public function edit(SpkKeputusan $keputusan, Kriteria $kriteria, SubKriteria $subkriteria)
    {
        $this->setKriteria($kriteria);

        $pageTitle = 'Edit Sub Kriteria: ' . $subkriteria->nama_subkriteria;

        return view('pages.admin.spk.kriteria.subkriteria.edit', compact('pageTitle') + [
            'subkriterium' => $subkriteria,
            'idKeputusan' => $this->idKeputusan,
            'keputusan' => $this->keputusan,
            'kriteria' => $this->kriteria,
        ]);
    }

    /**
     * Memperbarui data Sub Kriteria.
     * 
     * @param Request $request
     * @param SpkKeputusan $keputusan
     * @param Kriteria $kriteria
     * @param SubKriteria $subkriteria
     * @return \Illuminate\Http\RedirectResponse
     * @throws ModelNotFoundException
     */
    public function update(Request $request, SpkKeputusan $keputusan, Kriteria $kriteria, SubKriteria $subkriteria)
    {
        $this->setKriteria($kriteria);

        $request->validate([
            'nama_subkriteria' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0',
        ]);

        $subkriteria->update([
            'nama_subkriteria' => $request->nama_subkriteria,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('admin.spk.kriteria.subkriteria.index', [
            'keputusan' => $this->keputusan,
            'kriteria' => $this->kriteria
        ])->with('success', 'SubKriteria berhasil diperbarui.');
    }

    /**
     * Menghapus Sub Kriteria.
     * 
     * @param SpkKeputusan $keputusan
     * @param Kriteria $kriteria
     * @param SubKriteria $subkriteria
     * @return \Illuminate\Http\RedirectResponse
     * @throws ModelNotFoundException
     */
    public function destroy(SpkKeputusan $keputusan, Kriteria $kriteria, SubKriteria $subkriteria)
    {
        $this->setKriteria($kriteria);

        $subkriteria->delete();

        return redirect()->route('admin.spk.kriteria.subkriteria.index', [
            'keputusan' => $this->keputusan,
            'kriteria' => $this->kriteria
        ])->with('success', 'SubKriteria berhasil dihapus.');
    }
}