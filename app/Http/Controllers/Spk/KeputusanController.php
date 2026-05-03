<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SpkKeputusan;
use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\Penilaian;
use App\Models\HasilAkhir;
use App\Models\SubKriteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KeputusanController extends Controller
{
    public function index()
    {
        $keputusanList = SpkKeputusan::paginate(10);

        return view('pages.admin.spk.keputusan.index', [
            'keputusanList' => $keputusanList,
            'pageTitle'     => 'Manajemen SPK',
        ]);
    }

    public function create()
    {
        return view('pages.admin.spk.keputusan.create', [
            'pageTitle' => 'Buat Keputusan SPK Baru',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_keputusan' => 'required|string|max:255',
        ]);

        $keputusan = SpkKeputusan::create([
            'nama_keputusan' => $validated['nama_keputusan'],
            'tanggal_dibuat' => now(),
            'status'         => 'Draft',
        ]);

        return redirect()->route('admin.spk.index')
                         ->with('success', 'Keputusan SPK "' . $keputusan->nama_keputusan . '" berhasil dibuat!');
    }

    public function edit(SpkKeputusan $keputusan)
    {
        return view('pages.admin.spk.keputusan.edit', [
            'keputusan' => $keputusan,
            'pageTitle' => 'Edit Keputusan SPK',
        ]);
    }

    public function update(Request $request, SpkKeputusan $keputusan)
    {
        $validated = $request->validate([
            'nama_keputusan' => 'required|string|max:255',
        ]);

        $keputusan->update($validated);

        return redirect()->route('admin.spk.index')
                         ->with('success', 'Keputusan SPK "' . $keputusan->nama_keputusan . '" berhasil diperbarui.');
    }

    public function destroy(SpkKeputusan $keputusan)
    {
        DB::beginTransaction();
        try {
            $idKeputusan = $keputusan->id_keputusan;
            $nama      = $keputusan->nama_keputusan;

            $kriteriaIds   = Kriteria::where('id_keputusan', $idKeputusan)->pluck('id_kriteria')->toArray();
            $alternatifIds = Alternatif::where('id_keputusan', $idKeputusan)->pluck('id_alternatif')->toArray();

            if (!empty($kriteriaIds)) {
                Penilaian::whereIn('id_kriteria', $kriteriaIds)->delete();
                SubKriteria::whereIn('id_kriteria', $kriteriaIds)->delete();
            }

            if (!empty($alternatifIds)) {
                Penilaian::whereIn('id_alternatif', $alternatifIds)->delete();
                HasilAkhir::whereIn('id_alternatif', $alternatifIds)->delete();
            }

            Kriteria::where('id_keputusan', $idKeputusan)->delete();
            Alternatif::where('id_keputusan', $idKeputusan)->delete();

            $keputusan->delete();

            DB::commit();

            return redirect()->route('admin.spk.index')
                             ->with('success', 'Keputusan SPK "' . $nama . '" dan semua data terkait berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menghapus keputusan SPK: " . $e->getMessage(), ['id_keputusan' => $keputusan->id_keputusan]);

            return redirect()->route('admin.spk.index')
                             ->with('error', 'Gagal menghapus keputusan SPK: ' . $e->getMessage());
        }
    }
}