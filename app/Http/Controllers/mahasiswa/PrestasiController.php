<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Prestasi;
use App\Models\Admin\DataMahasiswa; // Fix namespace capitalization if needed
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PrestasiController extends Controller
{
    /**
     * Menampilkan daftar prestasi saya sendiri.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Pastikan user memiliki data mahasiswa
        if (!$user->mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $query = Prestasi::where('id_mahasiswa', $user->mahasiswa->id_mahasiswa);

        // Filter berdasarkan Status (Request User)
        if ($request->filled('status')) {
            $query->where('status_validasi', $request->status);
        }

        // Filter berdasarkan Tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $prestasi = $query->latest()->paginate(10);

        return view('pages.mahasiswa.prestasi.index', compact('prestasi'));
    }

    /**
     * Menampilkan form pengajuan prestasi baru.
     */
    public function create()
    {
        return view('pages.mahasiswa.prestasi.create');
    }

    /**
     * Menyimpan pengajuan prestasi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_prestasi' => 'required|in:Akademik,Non-Akademik',
            'tingkat_prestasi' => 'required|string',
            'juara' => 'required|string',
            'tahun' => 'required|digits:4|integer',
            'deskripsi' => 'nullable|string',
            'bukti_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Wajib ada bukti
        ]);

        $user = Auth::user();

        if (!$user->mahasiswa) {
            return redirect()->back()->with('error', 'Akun Anda belum terhubung dengan Data Mahasiswa. Silakan hubungi Admin.');
        }

        try {
            $buktiPath = null;
            if ($request->hasFile('bukti_file')) {
                $file = $request->file('bukti_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('prestasi', $filename, 'public');
                $buktiPath = 'prestasi/' . $filename;
            }

            Prestasi::create([
                'id_mahasiswa' => $user->mahasiswa->id_mahasiswa,
                'id_admin' => null, // Explicitly set null
                'nama_kegiatan' => $request->nama_kegiatan,
                'jenis_prestasi' => $request->jenis_prestasi,
                'tingkat_prestasi' => $request->tingkat_prestasi,
                'juara' => $request->juara,
                'tahun' => $request->tahun,
                'deskripsi' => $request->deskripsi,
                'status_validasi' => 'menunggu', 
                'bukti_path' => $buktiPath,
            ]);

            return redirect()->route('mahasiswa.prestasi.index')->with('success', 'Prestasi berhasil diajukan dan menunggu validasi.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail prestasi.
     */
    public function show($id)
    {
        $user = Auth::user();
        // Pastikan hanya bisa lihat punya sendiri
        $prestasi = Prestasi::where('id_mahasiswa', $user->mahasiswa->id_mahasiswa)
                            ->findOrFail($id);

        return view('pages.mahasiswa.prestasi.show', compact('prestasi'));
    }

    /**
     * Form edit (Hanya jika status 'menunggu' atau 'ditolak').
     */
    public function edit($id)
    {
        $user = Auth::user();
        $prestasi = Prestasi::where('id_mahasiswa', $user->mahasiswa->id_mahasiswa)
                            ->findOrFail($id);

        if ($prestasi->status_validasi == 'disetujui') {
            return redirect()->route('mahasiswa.prestasi.index')->with('error', 'Prestasi yang sudah disetujui tidak dapat diedit.');
        }

        return view('pages.mahasiswa.prestasi.edit', compact('prestasi'));
    }

    /**
     * Update prestasi.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $prestasi = Prestasi::where('id_mahasiswa', $user->mahasiswa->id_mahasiswa)
                            ->findOrFail($id);

        if ($prestasi->status_validasi == 'disetujui') {
            return back()->with('error', 'Tidak bisa mengubah data yang sudah disetujui.');
        }

        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_prestasi' => 'required|in:Akademik,Non-Akademik',
            'tingkat_prestasi' => 'required|string',
            'juara' => 'required|string',
            'tahun' => 'required|digits:4|integer',
            'deskripsi' => 'nullable|string',
            'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'nama_kegiatan' => $request->nama_kegiatan,
            'jenis_prestasi' => $request->jenis_prestasi,
            'tingkat_prestasi' => $request->tingkat_prestasi,
            'juara' => $request->juara,
            'tahun' => $request->tahun,
            'deskripsi' => $request->deskripsi,
            // Jika diedit setelah ditolak, kembalikan status ke menunggu
            'status_validasi' => 'menunggu', 
        ];

        if ($request->hasFile('bukti_file')) {
            // Hapus file lama jika ada
            if ($prestasi->bukti_path && Storage::disk('public')->exists($prestasi->bukti_path)) {
                Storage::disk('public')->delete($prestasi->bukti_path);
            }

            $file = $request->file('bukti_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('prestasi', $filename, 'public');
            $data['bukti_path'] = 'prestasi/' . $filename;
        }

        $prestasi->update($data);

        return redirect()->route('mahasiswa.prestasi.index')->with('success', 'Perubahan berhasil disimpan. Status kembali Menunggu Validasi.');
    }

    /**
     * Hapus prestasi (Hanya jika belum disetujui).
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $prestasi = Prestasi::where('id_mahasiswa', $user->mahasiswa->id_mahasiswa)
                            ->findOrFail($id);

        if ($prestasi->status_validasi == 'disetujui') {
            return back()->with('error', 'Tidak bisa menghapus prestasi yang sudah disetujui.');
        }

        if ($prestasi->bukti_path && Storage::disk('public')->exists($prestasi->bukti_path)) {
            Storage::disk('public')->delete($prestasi->bukti_path);
        }

        $prestasi->delete();

        return redirect()->route('mahasiswa.prestasi.index')->with('success', 'Data prestasi berhasil dihapus.');
    }
}
