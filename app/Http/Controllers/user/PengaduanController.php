<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\laporan\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->pengaduan()->latest();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $pengaduan = $query->paginate(10);
        return view('pages.user.pengaduan.index', compact('pengaduan'));
    }

    public function create()
    {
        return view('pages.user.pengaduan.create');
    }

    public function store(Request $request)
    {
        // VALIDASI
        $validatedData = $request->validate([
            'judul'            => 'required|string|max:255',
            'jenis_kasus'      => 'required|string|max:255',
            'deskripsi'        => 'required|string',
            'tanggal_pengaduan'=> 'nullable|date',
            'gambar_bukti'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // TAMBAH FIELD TAMBAHAN
        $validatedData['id_user'] = Auth::id();
        $validatedData['status'] = 'Terkirim';

        // UPLOAD FILE
        if ($request->hasFile('gambar_bukti')) {
            $validatedData['gambar_bukti'] = 
                $request->file('gambar_bukti')->store('bukti_pengaduan', 'public');
        }

        // SIMPAN
        Pengaduan::create($validatedData);

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Pengaduan berhasil dikirim!');
    }

    public function show($id)
    {
        $pengaduan = Auth::user()
            ->pengaduan()
            ->with('user')
            ->findOrFail($id);

        return view('pages.user.pengaduan.show', compact('pengaduan'));
    }

    public function destroy($id)
    {
        $pengaduan = Auth::user()->pengaduan()->findOrFail($id);

        // DELETE FILE BUKTI
        if ($pengaduan->gambar_bukti && Storage::disk('public')->exists($pengaduan->gambar_bukti)) {
            Storage::disk('public')->delete($pengaduan->gambar_bukti);
        }

        $pengaduan->delete();

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }
}
