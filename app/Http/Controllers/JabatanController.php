<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JabatanController extends Controller
{
    // Daftar jabatan dan divisi disesuaikan dengan struktur organisasi Anda
    private $jabatans = [
        'Gubernur', 
        'Wakil Gubernur', 
        'Sekretaris', 
        'Bendahara',
        'Kepala Divisi',
        'Anggota Divisi'
    ];
    
    private $divisis = [
        'BPH (Badan Pengurus Harian)',
        'Kaderisasi', 
        'Media Informasi', 
        'Technopreneurship', 
        'Public Relation'
    ];

    /**
     * Menampilkan daftar semua anggota.
     */
    public function index(): View
    {
        $anggota = Jabatan::latest()->paginate(10);
        return view('admin.jabatan.index', compact('anggota'));
    }

    /**
     * Menampilkan form untuk menambah anggota baru.
     */
    public function create(): View
    {
        return view('admin.jabatan.create', [
            'jabatans' => $this->jabatans,
            'divisis' => $this->divisis
        ]);
    }

    /**
     * Menyimpan anggota baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_anggota'       => 'required|string|min:3',
            'jabatan_struktural' => 'required|string',
            'divisi'             => 'required|string',
        ]);

        Jabatan::create($request->all());

        return redirect()->route('jabatan.index')->with(['success' => 'Data Anggota Berhasil Disimpan!']);
    }

    /**
     * Menampilkan form untuk mengedit data anggota.
     */
    public function edit(string $id): View
    {
        $jabatan = Jabatan::findOrFail($id);
        return view('admin.jabatan.edit', [
            'jabatan' => $jabatan,
            'jabatans' => $this->jabatans,
            'divisis' => $this->divisis
        ]);
    }

    /**
     * Memperbarui data anggota di database.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'nama_anggota'       => 'required|string|min:3',
            'jabatan_struktural' => 'required|string',
            'divisi'             => 'required|string',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        
        $jabatan->update($request->all());

        return redirect()->route('jabatan.index')->with(['success' => 'Data Anggota Berhasil Diperbarui!']);
    }

    /**
     * Menghapus data anggota dari database.
     */
    public function destroy($id): RedirectResponse
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();
        return redirect()->route('jabatan.index')->with(['success' => 'Data Anggota Berhasil Dihapus!']);
    }
}

