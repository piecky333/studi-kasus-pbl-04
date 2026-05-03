<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Spk\KeputusanDetailController;
use App\Models\Alternatif;
use App\Models\DataMahasiswa;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\HasilAkhir;
use App\Models\SpkKeputusan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AlternatifController extends KeputusanDetailController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index()
    {
        $alternatifList = Alternatif::where('id_keputusan', $this->idKeputusan)
            ->with(['penilaian', 'mahasiswa'])
            ->orderBy('nama_alternatif', 'asc')
            ->get();

        return view('pages.admin.spk.alternatif.index', [
            'idKeputusan'   => $this->idKeputusan,
            'keputusan'     => $this->keputusan,
            'alternatifList' => $alternatifList,
            'pageTitle'     => 'Manajemen Alternatif',
        ]);
    }

    public function create(Request $request)
    {
        $query = DataMahasiswa::select('id_mahasiswa', 'nim', 'nama', 'semester');

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('filter_prestasi')) {
            if ($request->filter_prestasi == 'ada') {
                $query->whereHas('prestasi');
            } elseif ($request->filter_prestasi == 'tidak_ada') {
                $query->whereDoesntHave('prestasi');
            }
        }

        if ($request->filled('filter_sanksi')) {
            if ($request->filter_sanksi == 'ada') {
                $query->whereHas('sanksi');
            } elseif ($request->filter_sanksi == 'tidak_ada') {
                $query->whereDoesntHave('sanksi');
            }
        }

        $mahasiswa = $query->orderBy('nama', 'asc')->get();

        $existingMahasiswaIds = Alternatif::where('id_keputusan', $this->idKeputusan)
                                          ->pluck('id_mahasiswa')
                                          ->toArray();

        return view('pages.admin.spk.alternatif.create', [
            'idKeputusan'          => $this->idKeputusan,
            'keputusan'            => $this->keputusan,
            'mahasiswaList'        => $mahasiswa,
            'existingMahasiswaIds' => $existingMahasiswaIds,
            'pageTitle'            => 'Tambah Alternatif Baru',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mahasiswa'   => 'required|array',
            'id_mahasiswa.*' => 'exists:mahasiswa,id_mahasiswa',
            'keterangan'     => 'nullable|string|max:1000',
        ]);

        $countBerhasil = 0;
        $countGagal    = 0;

        foreach ($validated['id_mahasiswa'] as $idMahasiswa) {
            $exists = Alternatif::where('id_keputusan', $this->idKeputusan)
                                ->where('id_mahasiswa', $idMahasiswa)
                                ->exists();

            if ($exists) {
                $countGagal++;
                continue;
            }

            $mhs = DataMahasiswa::find($idMahasiswa);
            if (!$mhs) continue;

            $alternatifBaru = Alternatif::create([
                'id_keputusan'   => $this->idKeputusan,
                'id_mahasiswa'   => $mhs->id_mahasiswa,
                'nama_alternatif' => $mhs->nama,
                'keterangan'     => $validated['keterangan'] ?? null,
            ]);

            $kriteriaList = Kriteria::where('id_keputusan', $this->idKeputusan)->get();
            foreach ($kriteriaList as $kriteria) {
                Penilaian::firstOrCreate(
                    [
                        'id_alternatif' => $alternatifBaru->id_alternatif,
                        'id_kriteria'   => $kriteria->id_kriteria,
                    ],
                    ['nilai' => 0]
                );
            }
            $countBerhasil++;
        }

        $message = "$countBerhasil alternatif berhasil ditambahkan.";
        if ($countGagal > 0) {
            $message .= " ($countGagal dilewati karena sudah ada)";
        }

        return redirect()->route('admin.spk.alternatif.index', $this->keputusan)
                         ->with('success', $message);
    }

    public function edit(SpkKeputusan $keputusan, Alternatif $alternatif)
    {
        return view('pages.admin.spk.alternatif.edit', [
            'idKeputusan' => $this->idKeputusan,
            'keputusan'   => $this->keputusan,
            'alternatif'  => $alternatif,
            'pageTitle'   => 'Edit Alternatif',
        ]);
    }

    public function update(Request $request, SpkKeputusan $keputusan, Alternatif $alternatif)
    {
        $validated = $request->validate([
            'nama_alternatif' => 'required|string|max:255',
            'id_mahasiswa'    => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('alternatif')->where(function ($query) use ($alternatif) {
                    return $query->where('id_keputusan', $this->idKeputusan)
                                 ->where('id_alternatif', '!=', $alternatif->id_alternatif);
                }),
            ],
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $alternatif->update($validated);

        return redirect()->route('admin.spk.alternatif.index', $this->keputusan)
                         ->with('success', 'Alternatif "' . $alternatif->nama_alternatif . '" berhasil diperbarui.');
    }

    public function bulkDestroy(Request $request, $idKeputusan)
    {
        $request->validate([
            'selected_alternatif'   => 'required|array',
            'selected_alternatif.*' => 'exists:alternatif,id_alternatif',
        ]);

        DB::beginTransaction();
        try {
            $ids = $request->selected_alternatif;

            Penilaian::whereIn('id_alternatif', $ids)->delete();
            HasilAkhir::whereIn('id_alternatif', $ids)->delete();
            Alternatif::whereIn('id_alternatif', $ids)->delete();

            DB::commit();

            return redirect()->route('admin.spk.alternatif.index', $this->keputusan)
                             ->with('success', count($ids) . ' alternatif berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.spk.alternatif.index', $this->idKeputusan)
                             ->with('error', 'Gagal menghapus alternatif: ' . $e->getMessage());
        }
    }

    public function destroy(SpkKeputusan $keputusan, Alternatif $alternatif)
    {
        DB::beginTransaction();
        try {
            $nama = $alternatif->nama_alternatif;

            Penilaian::where('id_alternatif', $alternatif->id_alternatif)->delete();
            HasilAkhir::where('id_alternatif', $alternatif->id_alternatif)->delete();

            $alternatif->delete();

            DB::commit();

            return redirect()->route('admin.spk.alternatif.index', $this->keputusan)
                             ->with('success', 'Alternatif "' . $nama . '" berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.spk.alternatif.index', $this->idKeputusan)
                             ->with('error', 'Gagal menghapus alternatif: ' . $e->getMessage());
        }
    }
}