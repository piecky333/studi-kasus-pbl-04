<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\laporan\pengaduan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // <-- TAMBAHKAN INI untuk mengelola file

class PengaduanController extends Controller
{
    /**
     * Opsi 1: Menampilkan semua daftar pengaduan (index)
     */
    public function index()
    {
        $daftarPengaduan = pengaduan::with('mahasiswa')
                                ->latest()
                                ->paginate(10);

        return view('pages.admin.pengaduan.index', compact('daftarPengaduan'));
    }

    /**
     * Opsi 2: Menampilkan detail spesifik pengaduan (show)
     */
    public function show($id)
    {
        $pengaduan = pengaduan::with(['mahasiswa', 'mahasiswa.user'])
                            ->findOrFail($id);

        // Di halaman show, Anda mungkin ingin menampilkan gambar
        // Anda bisa membuat variabel path gambar di sini
        $gambarUrl = null;
        if ($pengaduan->gambar_bukti_path) {
             // 'storage' adalah hasil dari `php artisan storage:link`
            $gambarUrl = Storage::url($pengaduan->gambar_bukti_path);
        }

        return view('pages.admin.pengaduan.show', compact('pengaduan', 'gambarUrl'));
    }

    /**
     * Opsi 3 (Aksi Kunci): Mem-verifikasi (Update Status) pengaduan.
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
     * Opsi 4: Menghapus pengaduan (destroy)
     *
     * =================================================================
     * === DISESUAIKAN: Menambahkan logika hapus file dari storage ===
     * =================================================================
     */
    public function destroy($id)
    {
        $pengaduan = pengaduan::findOrFail($id);

        // 1. Cek jika ada path gambar yang tersimpan
        // (Saya asumsikan nama kolomnya 'gambar_bukti_path' sesuai saran saya sebelumnya)
        if ($pengaduan->gambar_bukti_path) {

            // 2. Hapus file dari storage (public disk)
            // Ini akan menghapus file dari folder 'storage/app/public/bukti_pengaduan'
            Storage::disk('public')->delete($pengaduan->gambar_bukti_path);
        }

        // 3. Hapus data pengaduan dari database
        $pengaduan->delete();

        return redirect()->route('admin.pengaduan.index')
                         ->with('success', 'Pengaduan dan file bukti terkait telah berhasil dihapus.');
    }
}