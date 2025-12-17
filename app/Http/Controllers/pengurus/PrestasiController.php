<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Prestasi;
use App\Models\admin\Datamahasiswa;
use Illuminate\Support\Facades\Auth;

class PrestasiController extends Controller
{
    /**
     * Menampilkan daftar prestasi mahasiswa.
     */
    public function index(Request $request)
    {
        $query = Prestasi::with(['mahasiswa']);

        // Filter NIM
        if ($request->filled('nim')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('nim', 'like', $request->nim . '%');
            });
        }

        // Filter Tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter Tingkat
        if ($request->filled('tingkat')) {
            $query->where('tingkat_prestasi', $request->tingkat);
        }

        // Filter Jenis Prestasi
        if ($request->filled('jenis')) {
            $query->where('jenis_prestasi', $request->jenis);
        }

        // Sorting
        if ($request->filled('sort')) {
            if ($request->sort == 'oldest') {
                $query->orderBy('created_at', 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->latest();
        }

        $prestasi = $query->paginate(10);
        return view('pages.pengurus.prestasi.index', compact('prestasi'));
    }

    /**
     * Menampilkan form untuk menambahkan prestasi baru.
     */
    public function create()
    {
        $mahasiswa = Datamahasiswa::orderBy('nama', 'asc')->get();
        return view('pages.pengurus.prestasi.create', compact('mahasiswa'));
    }

    /**
     * Menyimpan data prestasi baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa'   => 'required|array',
            'id_mahasiswa.*' => 'exists:mahasiswa,id_mahasiswa',
            'judul_prestasi' => 'required|string|max:255',
            'jenis_prestasi' => 'required|in:Akademik,Non-Akademik',
            'tingkat'        => 'required|string|max:255',
            'tanggal'        => 'required|date',
            'deskripsi'      => 'nullable|string',
            'bukti_file'     => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_file')) {
            $file = $request->file('bukti_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('prestasi', $filename, 'public');
            $buktiPath = 'prestasi/' . $filename;
        }

        foreach ($request->id_mahasiswa as $id_mahasiswa) {
            Prestasi::create([
                'id_mahasiswa'   => $id_mahasiswa,
                'id_admin'       => null, // Pengurus create, admin null
                'nama_kegiatan'  => $request->judul_prestasi,
                'jenis_prestasi' => $request->jenis_prestasi,
                'tingkat_prestasi' => $request->tingkat,
                'tahun'          => date('Y', strtotime($request->tanggal)),
                'status_validasi' => 'menunggu', // Default menunggu verifikasi admin
                'deskripsi'      => $request->deskripsi,
                'bukti_path'     => $buktiPath,
            ]);
        }

        return redirect()->route('pengurus.prestasi.index')->with('success', 'Data prestasi berhasil diajukan dan menunggu verifikasi admin.');
    }

    /**
     * Menampilkan detail lengkap satu data prestasi.
     */
    public function show(string $id)
    {
        $prestasi = Prestasi::with('mahasiswa')->findOrFail($id);
        return view('pages.pengurus.prestasi.show', compact('prestasi'));
    }

    /**
     * Menampilkan form edit data prestasi.
     */
    public function edit(string $id)
    {
        $prestasi = Prestasi::with('mahasiswa')->findOrFail($id);
        
        // Cek jika status sudah disetujui, mungkin pengurus tidak boleh edit? 
        // Untuk sekarang biarkan bisa edit.
        
        return view('pages.pengurus.prestasi.edit', compact('prestasi'));
    }

    /**
     * Memperbarui data prestasi.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
            'tingkat_prestasi' => 'required|string|max:255',
            'tahun'            => 'required|digits:4|integer',
            'nim'              => 'nullable|exists:mahasiswa,nim',
        ]);

        $prestasi = Prestasi::findOrFail($id);
        
        $dataToUpdate = [
            'nama_kegiatan'    => $request->nama_kegiatan,
            'tingkat_prestasi' => $request->tingkat_prestasi,
            'tahun'            => $request->tahun,
            // Status tidak diupdate oleh pengurus di sini, tetap seperti sebelumnya atau reset ke menunggu?
            // Biasanya jika diedit, perlu verifikasi ulang.
            'status_validasi'  => 'menunggu', 
        ];

        if ($request->filled('nim')) {
            $mahasiswa = Datamahasiswa::where('nim', $request->nim)->first();
            if ($mahasiswa) {
                $dataToUpdate['id_mahasiswa'] = $mahasiswa->id_mahasiswa;
            }
        }

        $prestasi->update($dataToUpdate);

        return redirect()->route('pengurus.prestasi.index')->with('success', 'Data prestasi berhasil diperbarui dan menunggu verifikasi ulang.');
    }

    /**
     * Menghapus data prestasi.
     */
    public function destroy(string $id)
    {
        $prestasi = Prestasi::findOrFail($id);
        $prestasi->delete();

        return redirect()->route('pengurus.prestasi.index')->with('success', 'Data prestasi berhasil dihapus.');
    }

    // Reuse cariMahasiswa logic or just use Datamahasiswa model directly in view?
    // Admin used AJAX. I should probably add the ajax method here too if the view uses it.
    public function cariMahasiswa(Request $request)
    {
        $nim = $request->query('nim');

        if (!$nim) {
            return response()->json([
                'success' => false,
                'message' => 'NIM tidak boleh kosong'
            ]);
        }

        $mahasiswa = Datamahasiswa::where('nim', $nim)->first();

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
