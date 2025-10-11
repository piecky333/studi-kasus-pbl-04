<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Prestasi;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PrestasiController extends Controller
{
    /**
     * Menampilkan daftar semua data prestasi mahasiswa.
     */
    public function index(): View
    {
        $prestasis = Prestasi::latest()->paginate(10);
        return view('admin.prestasi.index', compact('prestasis'));
    }

    /**
     * Menampilkan form untuk menambah data baru.
     */
    public function create(): View
    {
        return view('admin.prestasi.create');
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi form
        $request->validate([
            'nama_prestasi' => 'required|string|max:255',
            'tingkat' => 'required|string|max:50',
            'peringkat' => 'nullable|string|max:50',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'id_dtmahasiswa' => 'required|integer|exists:dt_mahasiswa,id_dtmahasiswa'
        ]);

        // Buat data baru
        Prestasi::create([
            'nama_prestasi' => $request->nama_prestasi,
            'tingkat' => $request->tingkat,
            'peringkat' => $request->peringkat,
            'tahun' => $request->tahun,
            'id_dtmahasiswa' => $request->id_dtmahasiswa
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('prestasi.index')->with(['success' => 'Data Prestasi Berhasil Disimpan!']);
    }

    /**
     * Menampilkan form untuk mengedit data.
     */
    public function edit(string $id): View
    {
        $prestasi = Prestasi::findOrFail($id);
        return view('admin.prestasi.edit', compact('prestasi'));
    }

    /**
     * Memperbarui data di database.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // Validasi form
        $request->validate([
            'nama_prestasi' => 'required|string|max:255',
            'tingkat' => 'required|string|max:50',
            'peringkat' => 'nullable|string|max:50',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'id_dtmahasiswa' => 'required|integer|exists:dt_mahasiswa,id_dtmahasiswa'
        ]);

        $prestasi = Prestasi::findOrFail($id);

        // Update data
        $prestasi->update([
            'nama_prestasi' => $request->nama_prestasi,
            'tingkat' => $request->tingkat,
            'peringkat' => $request->peringkat,
            'tahun' => $request->tahun,
            'id_dtmahasiswa' => $request->id_dtmahasiswa
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('prestasi.index')->with(['success' => 'Data Prestasi Berhasil Diperbarui!']);
    }

    /**
     * Menghapus data dari database.
     */
    public function destroy($id): RedirectResponse
    {
        $prestasi = Prestasi::findOrFail($id);
        $prestasi->delete();
        return redirect()->route('prestasi.index')->with(['success' => 'Data Prestasi Berhasil Dihapus!']);
    }
}
