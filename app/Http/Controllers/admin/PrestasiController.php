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
            'nama_kegiatan' => 'required|string|max:200',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'tingkat' => 'required|in:Kampus,Kota,Provinsi,Nasional,Internasional',
            'jenis_prestasi' => 'required|in:Akademik,Non-Akademik',
            'status_validasi' => 'required|in:Tervalidasi,Menunggu,Ditolak',
            'id_mahasiswa' => 'required|integer|exists:dt_mahasiswas,id_mahasiswa'
        ]);

        // Buat data baru
        Prestasi::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'tahun' => $request->tahun,
            'tingkat' => $request->tingkat,
            'jenis_prestasi' => $request->jenis_prestasi,
            'status_validasi' => $request->status_validasi,
            'id_mahasiswa' => $request->id_mahasiswa
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
            'nama_kegiatan' => 'required|string|max:200',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'tingkat' => 'required|in:Kampus,Kota,Provinsi,Nasional,Internasional',
            'jenis_prestasi' => 'required|in:Akademik,Non-Akademik',
            'status_validasi' => 'required|in:Tervalidasi,Menunggu,Ditolak',
            'id_mahasiswa' => 'required|integer|exists:dt_mahasiswas,id_mahasiswa'
        ]);

        $prestasi = Prestasi::findOrFail($id);

        // Update data
        $prestasi->update([
            'nama_lomba' => $request->nama_lomba,
            'tahun' => $request->tahun,
            'tingkat' => $request->tingkat,
            'jenis_prestasi' => $request->jenis_prestasi,
            'status_validasi' => $request->status_validasi,
            'id_mahasiswa' => $request->id_mahasiswa
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