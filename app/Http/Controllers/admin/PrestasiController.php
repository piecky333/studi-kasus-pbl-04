<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Prestasi;
use App\Models\admin\Mahasiswa;
use Illuminate\Support\Facades\Auth;

class PrestasiController extends Controller
{
    /**
     * Tampilkan daftar prestasi mahasiswa.
     */
    public function index()
    {
        $prestasi = Prestasi::with(['mahasiswa'])->latest()->get();
        return view('pages.prestasi.index', compact('prestasi'));
    }

    /**
     * Form tambah prestasi.
     */
    public function create()
    {
        // Form tambah prestasi, NIM dicari via AJAX
        return view('pages.prestasi.create');
    }

    /**
     * Simpan data prestasi baru.
     */
    public function store(Request $request)
{
    $request->validate([
        'nim'         => 'required|exists:mahasiswa,nim',
        'judul_prestasi' => 'required|string|max:255',
        'tingkat'     => 'required|string|max:255',
        'tanggal'     => 'required|date',
        'deskripsi'   => 'nullable|string',
    ]);

    $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

    Prestasi::create([
        'id_mahasiswa'     => $mahasiswa->id_mahasiswa,
        'id_admin'         => Auth::user()->id_admin ?? null,
        'nama_kegiatan'    => $request->judul_prestasi,
        'tingkat_prestasi' => $request->tingkat,
        'tahun'            => date('Y', strtotime($request->tanggal)),
        'status_validasi'  => 'menunggu',
        'deskripsi'        => $request->deskripsi,
    ]);

    return redirect()->route('admin.prestasi.index')->with('success', 'Data prestasi berhasil ditambahkan.');
}


    /**
     * Detail satu data prestasi.
     */
    public function show(string $id)
    {
        $prestasi = Prestasi::with('mahasiswa')->findOrFail($id);
        return view('pages.prestasi.show', compact('prestasi'));
    }

    /**
     * Form edit prestasi.
     */
    public function edit(string $id)
    {
        $prestasi = Prestasi::with('mahasiswa')->findOrFail($id);
        return view('pages.prestasi.edit', compact('prestasi'));
    }

    /**
     * Update data prestasi.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
            'tingkat_prestasi' => 'required|string|max:255',
            'tahun'            => 'required|digits:4|integer',
            'status_validasi'  => 'required|in:menunggu,disetujui,ditolak',
        ]);

        $prestasi = Prestasi::findOrFail($id);
        $prestasi->update([
            'nama_kegiatan'    => $request->nama_kegiatan,
            'tingkat_prestasi' => $request->tingkat_prestasi,
            'tahun'            => $request->tahun,
            'status_validasi'  => $request->status_validasi,
        ]);

        return redirect()->route('admin.prestasi.index')->with('success', 'Data prestasi berhasil diperbarui.');
    }

    /**
     * Hapus data prestasi.
     */
    public function destroy(string $id)
    {
        $prestasi = Prestasi::findOrFail($id);
        $prestasi->delete();

        return redirect()->route('admin.prestasi.index')->with('success', 'Data prestasi berhasil dihapus.');
    }

    /**
     * AJAX: cari mahasiswa berdasarkan NIM.
     */
    public function cariMahasiswa(Request $request)
    {
        $nim = $request->query('nim');

        if (!$nim) {
            return response()->json([
                'success' => false,
                'message' => 'NIM tidak boleh kosong'
            ]);
        }

        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if ($mahasiswa) {
            return response()->json([
                'success' => true,
                'mahasiswa' => [
                    'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                    'nama' => $mahasiswa->nama,
                    'nim' => $mahasiswa->nim,
                    'email' => $mahasiswa->email,
                    'semester' => $mahasiswa->semester,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Mahasiswa tidak ditemukan'
        ]);
    }
}
