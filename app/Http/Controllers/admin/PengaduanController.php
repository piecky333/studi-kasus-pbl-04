<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\laporan\pengaduan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * Menampilkan daftar pengaduan
     */
    public function index()
    {
        $daftarPengaduan = pengaduan::with('mahasiswa')
                                ->latest()
                                ->paginate(10);

        return view('pages.admin.pengaduan.index', compact('daftarPengaduan'));
    }

    /**
     * Menampilkan detail satu pengaduan
     */
    public function show($id)
    {
        $pengaduan = pengaduan::with(['mahasiswa', 'mahasiswa.user'])
                            ->findOrFail($id);

        // LOGIKA BARU: Otomatis ubah status ke 'Diproses' jika masih 'Terkirim'
        // Ini akan menghilangkan notifikasi (karena notifikasi hanya menghitung 'Terkirim')
        if ($pengaduan->status === 'Terkirim') {
            $pengaduan->status = 'Diproses';
            $pengaduan->save();
        }

        $gambarUrl = null;
        if ($pengaduan->gambar_bukti) { // gunakan nama kolom baru
            $gambarUrl = asset('storage/' . $pengaduan->gambar_bukti);
        }

        return view('pages.admin.pengaduan.show', compact('pengaduan', 'gambarUrl'));
    }

    /**
     * VALIDASI STATUS (admin update status)
     */
    public function verifikasi(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in(['Diproses', 'Selesai', 'Ditolak']),
            ],
        ]);

        $pengaduan = pengaduan::findOrFail($id);
        $pengaduan->status = $validated['status'];
        $pengaduan->save();

        return redirect()->route('admin.pengaduan.show', $pengaduan->id_pengaduan)
                         ->with('success', 'Status pengaduan berhasil diperbarui!');
    }

    /**
     * MENGHAPUS DATA + MENGHAPUS FILE BUKTI
     */
    public function destroy($id)
    {
        $pengaduan = pengaduan::findOrFail($id);

        // Hapus file dari storage jika ada
        if ($pengaduan->gambar_bukti) {
            Storage::disk('public')->delete($pengaduan->gambar_bukti);
        }

        $pengaduan->delete();

        return redirect()->route('admin.pengaduan.index')
                         ->with('success', 'Pengaduan dan file buktinya berhasil dihapus!');
    }


    /**
     * =========================================
     *  METHOD STORE (USER / MAHASISWA)
     *  =========================================
     *  Inilah yang MENYIMPAN FILE GAMBAR
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul'             => 'required|string|max:255',
            'tanggal_pengaduan' => 'required|date',
            'jenis_kasus'       => 'required|string|max:255',
            'deskripsi'         => 'required|string',
            'gambar_bukti'      => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validatedData['id_user'] = auth()->id();
        $validatedData['status'] = 'Pending';

        // PROSES UPLOAD FILE
        if ($request->hasFile('gambar_bukti')) {
            $validatedData['gambar_bukti'] =
                $request->file('gambar_bukti')->store('pengaduan', 'public');
        }

        pengaduan::create($validatedData);

        return redirect()->route('user.pengaduan.index')
                         ->with('success', 'Pengaduan berhasil dikirim!');
    }
}
