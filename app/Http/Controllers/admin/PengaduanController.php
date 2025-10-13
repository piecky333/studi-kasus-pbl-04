<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\laporan\Pengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    // 🔹 Tampilkan semua pengaduan di halaman admin
    public function index()
    {
        $pengaduan = Pengaduan::with('user')->orderBy('created_at', 'desc')->get();
        return view('pages.pengaduan.index', compact('pengaduan'));
    }

    // 🔹 Detail pengaduan
    public function show($id)
    {
        $pengaduan = Pengaduan::with('user')->findOrFail($id);
        return view('pages.pengaduan.show', compact('pengaduan'));
    }

    // 🔹 Update status pengaduan
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:menunggu,proses,selesai',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->update(['status_validasi' => $request->status_validasi]);

        return redirect()->back()->with('success', 'Status pengaduan berhasil diperbarui!');
    }

    // 🔹 Hapus pengaduan
    public function destroy($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->delete();

        return redirect()->back()->with('success', 'Pengaduan berhasil dihapus.');
    }
}
