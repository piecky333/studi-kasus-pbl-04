<?php

namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\laporan\Pengaduan;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    /**
     * Menampilkan daftar semua pengaduan (misal: untuk halaman publik).
     */
    public function index(Request $request)
    {
        // pengaduan milik user yang sedang login
        $query = Auth::user()->pengaduan()->latest();

        // Cek jika ada input pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // data dengan paginasi
        $pengaduan = $query->paginate(10);

        // Mengirim data ke view
        return view('user.pengaduan.index', compact('pengaduan'));
    }

    /**
     * Menampilkan form untuk membuat pengaduan baru.
     */
    public function create()
    {
        return view('user.pengaduan.create');
    }

    /**
     * Menyimpan pengaduan baru ke database.
     */
    public function store(Request $request)
    {
        // DIPERBAIKI: Aturan validasi disesuaikan
        $request->validate([
            'judul' => 'required|string|max:255',
            'jenis_kasus' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        //proses pembuatan data
        Pengaduan::create([

            //mengambil id user yang sedang login
            'id_user' => Auth::id(),
            'judul' => $request->judul,
            'jenis_kasus' => $request->jenis_kasus,
            'deskripsi' => $request->deskripsi,
            'status' => 'Terkirim',

        ]);

        //halaman dashnoard user
        return redirect()->route('dashboard')
            ->with('success', 'Pengaduan berhasil dikirim!');
    }

    /**
     * Menampilkan detail satu pengaduan.
     */
    public function show($id)
    {
        //pengambilan data pengaduan
        $pengaduan = Pengaduan::where('id_user', Auth::id())->with('user')->findOrFail($id);
        return view('user.pengaduan.show', compact('pengaduan'));
    }

    /**
     * Menghapus pengaduan.
     */
    public function destroy($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        //hanya admin atau user yang membuat pengaduan yang boleh menghapus
        if (Auth::id() !== $pengaduan->id_user && !Auth::user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki izin untuk melakukan aksi ini.');
        }

        $pengaduan->delete();
        return redirect()->route('dashboard')->with('success', 'Pengaduan berhasil dihapus.');
    }
}
