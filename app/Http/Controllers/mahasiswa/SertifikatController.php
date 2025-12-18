<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\mahasiswa\PengajuanSertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    /**
     * Menampilkan daftar pengajuan sertifikat mahasiswa.
     * 
     * Data diurutkan dari yang terbaru dan dipaginasi (10 item per halaman).
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $sertifikat = PengajuanSertifikat::where('id_user', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('pages.mahasiswa.sertifikat.index', compact('sertifikat'));
    }

    /**
     * Menampilkan form untuk mengajukan sertifikat baru.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.mahasiswa.sertifikat.create');
    }

    /**
     * Menyimpan data pengajuan sertifikat baru.
     * 
     * Proses:
     * 1. Validasi input form (nama kegiatan, jenis, tanggal, file).
     * 2. Upload file sertifikat ke storage publik.
     * 3. Simpan record ke database dengan status 'pending'.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|string|max:100', // e.g., Akademik, Non-Akademik
            'tingkat_kegiatan' => 'required|string|max:100', // Lokal, Provinsi, Nasional, Internasional
            'tanggal_kegiatan' => 'required|date',
            'file_sertifikat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('file_sertifikat')) {
            $path = $request->file('file_sertifikat')->store('sertifikat_mahasiswa', 'public');
        }

        PengajuanSertifikat::create([
            'id_user' => Auth::id(),
            'nama_kegiatan' => $request->nama_kegiatan,
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'tingkat_kegiatan' => $request->tingkat_kegiatan,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'file_sertifikat' => $path,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending',
        ]);

        return redirect()->route('mahasiswa.sertifikat.index')->with('success', 'Pengajuan sertifikat berhasil dikirim!');
    }
}
