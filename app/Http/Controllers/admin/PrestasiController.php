<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Prestasi;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;

class PrestasiController extends Controller
{
    /**
     * Tampilkan daftar prestasi mahasiswa.
     */
    public function index()
    {
        $prestasi = Prestasi::with(['mahasiswa'])->latest()->get();
        return view('pages.admin.prestasi.index', compact('prestasi'));
    }

    /**
     * Form tambah prestasi.
     */
    public function create()
    {
        return view('pages.admin.prestasi.create');
    }

    /**
     * Simpan data prestasi baru.
     *
     * === PERUBAHAN DI SINI ===
     * Kita tidak lagi memvalidasi 'nim'.
     * Kita memvalidasi 'id_mahasiswa' yang didapat dari AJAX.
     */
    public function store(Request $request)
    {
        // Jika id_mahasiswa kosong tapi nim ada, cari id_mahasiswa berdasarkan nim
        if (empty($request->id_mahasiswa) && !empty($request->nim)) {
            $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();
            if ($mahasiswa) {
                $request->merge(['id_mahasiswa' => $mahasiswa->id_mahasiswa]);
            }
        }

        $request->validate([
            'id_mahasiswa' => 'required|integer|exists:mahasiswa,id_mahasiswa',
            'judul_prestasi' => 'required|string|max:255',
            'tingkat'      => 'required|string|max:255',
            'tanggal'      => 'required|date',
            'deskripsi'    => 'nullable|string',
        ]);

        Prestasi::create([
            'id_mahasiswa'   => $request->id_mahasiswa,
            'id_admin'       => Auth::user()->id_admin ?? null,
            'nama_kegiatan'  => $request->judul_prestasi,
            'tingkat_prestasi' => $request->tingkat,
            'tahun'          => date('Y', strtotime($request->tanggal)),
            'status_validasi' => 'menunggu',
            'deskripsi'      => $request->deskripsi,
        ]);

        return redirect()->route('admin.prestasi.index')->with('success', 'Data prestasi berhasil ditambahkan.');
    }


    /**
     * Detail satu data prestasi.
     */
    public function show(string $id)
    {
        $prestasi = Prestasi::with('mahasiswa')->findOrFail($id);
        return view('pages.admin.prestasi.show', compact('prestasi'));
    }

    /**
     * Form edit prestasi.
     */
    public function edit(string $id)
    {
        $prestasi = Prestasi::with('mahasiswa')->findOrFail($id);
        return view('pages.admin.prestasi.edit', compact('prestasi'));
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
            'nim'              => 'nullable|exists:mahasiswa,nim', // Validasi NIM jika ada
        ]);

        $prestasi = Prestasi::findOrFail($id);
        
        $dataToUpdate = [
            'nama_kegiatan'    => $request->nama_kegiatan,
            'tingkat_prestasi' => $request->tingkat_prestasi,
            'tahun'            => $request->tahun,
            'status_validasi'  => $request->status_validasi,
        ];

        // Jika NIM berubah, update id_mahasiswa
        if ($request->filled('nim')) {
            $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();
            if ($mahasiswa) {
                $dataToUpdate['id_mahasiswa'] = $mahasiswa->id_mahasiswa;
            }
        }

        $prestasi->update($dataToUpdate);

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
     *
     * === PERUBAHAN DI SINI (ASUMSI) ===
     * Saya asumsikan primary key Anda adalah 'id_mahasiswa'.
     * Jika primary key Anda hanya 'id', ganti 'id_mahasiswa' => $mahasiswa->id_mahasiswa
     * menjadi 'id_mahasiswa' => $mahasiswa->id
     *
     * Dan pastikan kolom di tabel mahasiswa juga 'id_mahasiswa'
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
                    // PASTIKAN NAMA KOLOM INI BENAR
                    // Jika primary key di tabel mahasiswa adalah 'id', ganti di bawah ini menjadi $mahasiswa->id
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