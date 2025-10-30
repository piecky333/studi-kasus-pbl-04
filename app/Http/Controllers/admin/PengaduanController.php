<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\laporan\pengaduan; 
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        return view('pages.admin.pengaduan.show', compact('pengaduan'));
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
     */
    public function destroy($id)
    {
        $pengaduan = pengaduan::findOrFail($id);

        // Hapus data dari database
        $pengaduan->delete();

        return redirect()->route('admin.pengaduan.index')
                         ->with('success', 'Pengaduan telah berhasil dihapus.');
    }
}

