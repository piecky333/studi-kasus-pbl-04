<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\laporan\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * Menampilkan daftar pengaduan yang telah dibuat oleh user.
     *
     * Fitur filtering:
     * - Status (Pending/Diproses/Selesai/Ditolak)
     * - Pencarian Judul
     * - Sorting (Terbaru/Terlama)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Auth::user()->pengaduan();

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Sort
        if ($request->filled('sort') && $request->sort == 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $pengaduan = $query->paginate(10);
        return view('pages.user.pengaduan.index', compact('pengaduan'));
    }

    /**
     * Menampilkan halaman form untuk membuat pengaduan baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.user.pengaduan.create');
    }

    /**
     * Menyimpan pengaduan baru ke database.
     *
     * Proses:
     * 1. Validasi input (Judul, Jenis, Deskripsi, dll).
     * 2. Upload file bukti (optional).
     * 3. Set status default 'Terkirim'.
     * 4. Redirect sesuai role user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input.
        $validatedData = $request->validate([
            'judul'            => 'required|string|max:255',
            'jenis_kasus'      => 'required|string|max:255',
            'deskripsi'        => 'required|string',
            'tanggal_pengaduan'=> 'nullable|date',
            'gambar_bukti'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'no_telpon_dihubungi' => 'required|string|max:20', // Validasi nomor telepon
        ]);

        // Set data tambahan.
        $validatedData['id_user'] = Auth::id();
        $validatedData['status'] = 'Terkirim';

        // Upload file bukti.
        if ($request->hasFile('gambar_bukti')) {
            $validatedData['gambar_bukti'] = 
                $request->file('gambar_bukti')->store('bukti_pengaduan', 'public');
        }

        // Simpan data.
        Pengaduan::create($validatedData);

        $redirectRoute = Auth::user()->role === 'mahasiswa' ? 'mahasiswa.dashboard' : 'user.dashboard';

        return redirect()
            ->route($redirectRoute)
            ->with('success', 'Pengaduan berhasil dikirim!');
    }

    /**
     * Menampilkan detail lengkap dari sebuah pengaduan.
     *
     * Memuat relasi tanggapan untuk menampilkan diskusi antara user dan admin.
     *
     * @param int $id ID Pengaduan
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show($id)
    {
        $pengaduan = Auth::user()
            ->pengaduan()
            ->with(['user', 'tanggapan.admin', 'tanggapan.user']) // Eager load tanggapan
            ->findOrFail($id);

        return view('pages.user.pengaduan.show', compact('pengaduan'));
    }

    /**
     * Menyimpan balasan/tanggapan dari user pada pengaduan.
     * 
     * Memungkinkan komunikasi dua arah (chat) dalam tiket pengaduan.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  ID Pengaduan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTanggapan(Request $request, $id)
    {
        $request->validate([
            'isi_tanggapan' => 'required|string',
        ]);

        $pengaduan = Auth::user()->pengaduan()->findOrFail($id);

        \App\Models\laporan\Tanggapan::create([
            'id_pengaduan' => $id,
            'id_user' => Auth::id(),
            'isi_tanggapan' => $request->isi_tanggapan,
            'tanggal_tanggapan' => now(),
        ]);

        return back()->with('success', 'Tanggapan berhasil dikirim.');
    }

    /**
     * Menghapus pengaduan yang dibuat oleh user sendiri.
     * 
     * Hapus data pengaduan beserta file bukti fisiknya dari storage.
     *
     * @param int $id ID Pengaduan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $pengaduan = Auth::user()->pengaduan()->findOrFail($id);

        // Hapus file bukti.
        if ($pengaduan->gambar_bukti && Storage::disk('public')->exists($pengaduan->gambar_bukti)) {
            Storage::disk('public')->delete($pengaduan->gambar_bukti);
        }

        $pengaduan->delete();

        $redirectRoute = Auth::user()->role === 'mahasiswa' ? 'mahasiswa.dashboard' : 'user.dashboard';

        return redirect()
            ->route($redirectRoute)
            ->with('success', 'Pengaduan berhasil dihapus.');
    }
}
