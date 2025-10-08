<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JabatanController extends Controller
{
    // Daftar pilihan jabatan yang akan kita gunakan sebagai "enum"
    private $jabatanOptions = [
        'Staff Administrasi',
        'Frontend Web Developer',
        'Backend Web Developer',
        'UI/UX Designer',
        'Project Manager',
        'Kepala Divisi',
        'Direktur'
    ];

    /**
     * Menampilkan daftar semua data jabatan pegawai.
     */
    public function index(): View
    {
        $jabatans = Jabatan::latest()->paginate(10);
        return view('admin.jabatan.index', compact('jabatans'));
    }

    /**
     * Menampilkan form untuk menambah data baru.
     */
    public function create(): View
    {
        return view('admin.jabatan.create', ['jabatanOptions' => $this->jabatanOptions]);
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi form
        $request->validate([
            'nama'      => 'required|string|min:3',
            'jabatan'   => 'required|string',
            'deskripsi' => 'nullable|string'
        ]);

        // Buat data baru
        Jabatan::create([
            'nama'      => $request->nama,
            'jabatan'   => $request->jabatan,
            'deskripsi' => $request->deskripsi
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('jabatan.index')->with(['success' => 'Data Pegawai Berhasil Disimpan!']);
    }

    /**
     * Menampilkan form untuk mengedit data.
     */
    public function edit(string $id): View
    {
        $jabatan = Jabatan::findOrFail($id);
        return view('jabatan.edit', [
            'jabatan' => $jabatan, 
            'jabatanOptions' => $this->jabatanOptions
        ]);
    }

    /**
     * Memperbarui data di database.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // Validasi form
        $request->validate([
            'nama'      => 'required|string|min:3',
            'jabatan'   => 'required|string',
            'deskripsi' => 'nullable|string'
        ]);

        $jabatan = Jabatan::findOrFail($id);

        // Update data
        $jabatan->update([
            'nama'      => $request->nama,
            'jabatan'   => $request->jabatan,
            'deskripsi' => $request->deskripsi
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('jabatan.index')->with(['success' => 'Data Pegawai Berhasil Diperbarui!']);
    }

    /**
     * Menghapus data dari database.
     */
    public function destroy($id): RedirectResponse
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();
        return redirect()->route('jabatan.index')->with(['success' => 'Data Pegawai Berhasil Dihapus!']);
    }
}

