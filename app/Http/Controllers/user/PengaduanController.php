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
     * Tampilkan daftar pengaduan user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Auth::user()->pengaduan()->latest();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $pengaduan = $query->paginate(10);
        return view('pages.user.pengaduan.index', compact('pengaduan'));
    }

    /**
     * Tampilkan form buat pengaduan.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.user.pengaduan.create');
    }

    /**
     * Simpan pengaduan baru.
     * Upload bukti jika ada.
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

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Pengaduan berhasil dikirim!');
    }

    /**
     * Tampilkan detail pengaduan.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $pengaduan = Auth::user()
            ->pengaduan()
            ->with('user')
            ->findOrFail($id);

        return view('pages.user.pengaduan.show', compact('pengaduan'));
    }

    /**
     * Hapus pengaduan.
     * Hapus file bukti terkait.
     *
     * @param int $id
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

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }
}
