<?php

namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\laporan\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Pastikan ini ada

class PengaduanController extends Controller
{
    /**
     * Menampilkan daftar semua pengaduan milik user.
     */
    public function index(Request $request)
    {
        // Pengaduan milik user yang sedang login
        $query = Auth::user()->pengaduan()->latest();

        // Cek jika ada input pencarian
        if ($request->filled('search')) { // Gunakan filled() untuk cek lebih baik
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Data dengan paginasi
        $pengaduan = $query->paginate(10);

        // Mengirim data ke view
        return view('pages.user.pengaduan.index', compact('pengaduan'));
    }

    /**
     * Menampilkan form untuk membuat pengaduan baru.
     */
    public function create()
    {
        // === KOREKSI ===
        // Path view harus lengkap dari folder 'views'
        return view('pages.user.pengaduan.create'); 
    }

    /**
     * Menyimpan pengaduan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'jenis_kasus' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            // Gambar tidak divalidasi/diupload sesuai koreksi sebelumnya
        ]);

        // Tambahkan id_user dan status default
        $validatedData['id_user'] = Auth::id();
        $validatedData['status'] = 'Terkirim'; 
        // Anda mungkin perlu menambahkan 'tanggal_pengaduan' jika tidak otomatis
        // $validatedData['tanggal_pengaduan'] = now(); 

        Pengaduan::create($validatedData);

        // Redirect ke halaman dashboard user
        return redirect()->route('user.dashboard')
                         ->with('success', 'Pengaduan berhasil dikirim!');
    }

    /**
     * Menampilkan detail satu pengaduan.
     */
    public function show($id)
    {
        // Ambil pengaduan milik user ini, atau 404
        // Eager load 'user' (walaupun sudah tahu user-nya, mungkin berguna di view)
        $pengaduan = Auth::user()->pengaduan()->with('user')->findOrFail($id);
        
        return view('pages.user.pengaduan.show', compact('pengaduan'));
    }

    /**
     * Menghapus pengaduan.
     */
    public function destroy($id)
    {
        // Cari pengaduan milik user yang sedang login
        $pengaduan = Auth::user()->pengaduan()->findOrFail($id);

        // === KOREKSI (Sesuai Struktur Database Anda) ===
        // Hapus logika Storage::delete() karena tidak ada kolom 'image'

        $pengaduan->delete();

        return redirect()->route('user.dashboard')->with('success', 'Pengaduan berhasil dihapus.');
    }
}

