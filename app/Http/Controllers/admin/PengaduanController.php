<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\laporan\pengaduan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

/**
 * Class PengaduanController
 * 
 * Controller ini menangani manajemen Pengaduan/Laporan dari mahasiswa.
 * Fitur mencakup:
 * 1. Admin: Melihat daftar, detail, memverifikasi, dan menghapus pengaduan.
 * 2. User (Mahasiswa): Membuat pengaduan baru (via method store).
 * 
 * @package App\Http\Controllers\Admin
 */
class PengaduanController extends Controller
{
    /**
     * Menampilkan daftar pengaduan yang masuk.
     * 
     * Data diurutkan berdasarkan yang terbaru dan dipaginasi.
     * Menggunakan Eager Loading 'mahasiswa' untuk efisiensi.
     * 
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = pengaduan::with('mahasiswa');

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            // Default sorting
            $query->orderBy('created_at', 'desc');
        }

        $daftarPengaduan = $query->paginate(10);

        return view('pages.admin.pengaduan.index', compact('daftarPengaduan'));
    }

    /**
     * Menampilkan detail lengkap satu pengaduan.
     * 
     * Fitur Otomatis:
     * Jika admin membuka pengaduan yang statusnya masih 'Terkirim',
     * sistem akan otomatis mengubah statusnya menjadi 'Diproses'.
     * Hal ini memberikan feedback implisit bahwa laporan sudah dilihat admin.
     * 
     * @param int $id ID Pengaduan
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show($id)
    {
        $pengaduan = pengaduan::with(['mahasiswa', 'mahasiswa.user'])
                            ->findOrFail($id);

        // LOGIKA OTOMATISASI STATUS:
        // Ubah status ke 'Diproses' jika status awal adalah 'Terkirim'.
        // Ini menandakan bahwa admin telah membuka dan membaca detail laporan ini.
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
     * Memperbarui status pengaduan (Verifikasi).
     * 
     * Admin dapat mengubah status menjadi:
     * - Diproses: Sedang ditindaklanjuti.
     * - Selesai: Masalah telah teratasi.
     * - Ditolak: Laporan tidak valid atau tidak dapat diproses.
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
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
     * Menghapus data pengaduan beserta bukti pendukungnya.
     * 
     * File gambar bukti (jika ada) akan dihapus dari storage fisik
     * untuk menjaga kebersihan server.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
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
     * Menyimpan pengaduan baru (Untuk User/Mahasiswa).
     * 
     * Method ini menangani:
     * 1. Validasi input form pengaduan.
     * 2. Upload file bukti (gambar) ke storage publik.
     * 3. Set status awal menjadi 'Pending' (atau 'Terkirim' sesuai logika sistem).
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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
