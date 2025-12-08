<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Prestasi;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;

/**
 * Class PrestasiController
 * 
 * Controller ini bertanggung jawab untuk mengelola data Prestasi Mahasiswa.
 * Fitur mencakup CRUD lengkap dan pencarian data mahasiswa via AJAX untuk
 * memudahkan pengisian form.
 * 
 * @package App\Http\Controllers\Admin
 */
class PrestasiController extends Controller
{
    /**
     * Menampilkan daftar prestasi mahasiswa.
     * 
     * Menggunakan Eager Loading 'mahasiswa' untuk efisiensi query.
     * 
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Prestasi::with(['mahasiswa']);

        // Filter NIM (Starts With)
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

        $prestasi = $query->latest()->paginate(10);
        return view('pages.admin.prestasi.index', compact('prestasi'));
    }

    /**
     * Menampilkan form untuk menambahkan prestasi baru.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.admin.prestasi.create');
    }

    /**
     * Menyimpan data prestasi baru ke database.
     * 
     * Fitur Cerdas:
     * Mendukung pencarian ID Mahasiswa berdasarkan NIM jika ID tidak dikirim langsung.
     * Ini berguna jika input form menggunakan autocomplete NIM.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Logika Fallback:
        // Jika form mengirimkan NIM tetapi tidak mengirimkan ID Mahasiswa (misal JS error),
        // sistem akan mencoba mencari ID Mahasiswa secara manual berdasarkan NIM tersebut.
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
            'bukti_file'   => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048', // Max 2MB
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_file')) {
            $file = $request->file('bukti_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan ke storage/app/public/prestasi
            $path = $file->storeAs('prestasi', $filename, 'public');
            $buktiPath = 'prestasi/' . $filename;
        }

        Prestasi::create([
            'id_mahasiswa'   => $request->id_mahasiswa,
            'id_admin'       => Auth::user()->id_admin ?? null,
            'nama_kegiatan'  => $request->judul_prestasi,
            'tingkat_prestasi' => $request->tingkat,
            'tahun'          => date('Y', strtotime($request->tanggal)),
            'status_validasi' => 'disetujui',
            'deskripsi'      => $request->deskripsi,
            'bukti_path'     => $buktiPath,
        ]);

        return redirect()->route('admin.prestasi.index')->with('success', 'Data prestasi berhasil ditambahkan.');
    }


    /**
     * Menampilkan detail lengkap satu data prestasi.
     * 
     * @param string $id ID Prestasi
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(string $id)
    {
        $prestasi = Prestasi::with('mahasiswa')->findOrFail($id);
        return view('pages.admin.prestasi.show', compact('prestasi'));
    }

    /**
     * Menampilkan form edit data prestasi.
     * 
     * @param string $id
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function edit(string $id)
    {
        $prestasi = Prestasi::with('mahasiswa')->findOrFail($id);
        return view('pages.admin.prestasi.edit', compact('prestasi'));
    }

    /**
     * Memperbarui data prestasi yang sudah ada.
     * 
     * Mendukung pembaruan relasi mahasiswa jika NIM diubah.
     * 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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

        // Logika Update Relasi:
        // Jika field NIM diisi/diubah, kita perlu mencari ulang ID Mahasiswa terkait
        // dan mengupdate foreign key 'id_mahasiswa' di tabel prestasi.
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
     * Menghapus data prestasi secara permanen.
     * 
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function destroy(string $id)
    {
        $prestasi = Prestasi::findOrFail($id);
        $prestasi->delete();

        return redirect()->route('admin.prestasi.index')->with('success', 'Data prestasi berhasil dihapus.');
    }

    /**
     * Endpoint AJAX: Mencari data mahasiswa berdasarkan NIM.
     * 
     * Digunakan oleh frontend (JavaScript) untuk fitur autofill/autocomplete.
     * Mengembalikan response JSON berisi data mahasiswa jika ditemukan.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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