<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Admin\Pengurus;
use App\Models\Admin\Divisi;
use App\Models\User;
use Illuminate\Http\Request;

class PengurusController extends Controller
{
    /**
     * Tampilkan daftar pengurus.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = pengurus::with(['divisi', 'user.mahasiswa', 'jabatan']);

        // Filter by Search (Nama, Divisi, Jabatan)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('jabatan', function($q) use ($search) {
                      $q->where('nama_jabatan', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user.mahasiswa', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                  })
                  ->orWhereHas('divisi', function($q) use ($search) {
                      $q->where('nama_divisi', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Divisi
        if ($request->has('id_divisi') && $request->id_divisi != '') {
            $query->where('id_divisi', $request->id_divisi);
        }

        // Filter by Semester
        if ($request->has('semester') && $request->semester != '') {
            $semester = $request->semester;
            $query->whereHas('user.mahasiswa', function($q) use ($semester) {
                $q->where('semester', $semester);
            });
        }

        $pengurus = $query->latest()->paginate(10)->withQueryString();

        // Data for Filters
        $divisi = divisi::all();
        // Get distinct semesters from Data Mahasiswa that are linked to users
        $semesters = \App\Models\Admin\DataMahasiswa::select('semester')->distinct()->orderBy('semester')->pluck('semester');

        return view('pages.pengurus.pengurus.index', compact('pengurus', 'divisi', 'semesters'));
    }

    /**
     * Tampilkan form tambah pengurus.
     *
     * @return \Illuminate\View\View
     */
    /**
     * Tampilkan form tambah pengurus.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $divisi = divisi::all();
        // Ambil data mahasiswa yang memiliki id_user (sudah punya akun)
        $mahasiswa = \App\Models\Admin\DataMahasiswa::whereNotNull('id_user')->get();
        $jabatan = \App\Models\Jabatan::all();
        return view('pages.pengurus.pengurus.create', compact('divisi', 'mahasiswa', 'jabatan'));
    }

    /**
     * Simpan data pengurus baru.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'id_user' => 'required|exists:user,id_user',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
        ]);

        // Simpan data pengurus
        pengurus::create($request->all());

        // Update role user menjadi 'pengurus'
        $user = User::findOrFail($request->id_user);
        $user->role = 'pengurus';
        $user->save();

        return redirect()->route('pengurus.pengurus.index')->with('success', 'Pengurus berhasil ditambahkan dan role user diperbarui!');
    }

    /**
     * Tampilkan form edit pengurus.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $pengurus = pengurus::findOrFail($id);
        $divisi = divisi::all();
        // Ambil data mahasiswa yang memiliki id_user
        $mahasiswa = \App\Models\Admin\DataMahasiswa::whereNotNull('id_user')->get();
        $jabatan = \App\Models\Jabatan::all();
        return view('pages.pengurus.pengurus.edit', compact('pengurus', 'divisi', 'mahasiswa', 'jabatan'));
    }

    /**
     * Perbarui data pengurus.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'id_user' => 'required|exists:user,id_user',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
        ]);

        $pengurus = pengurus::findOrFail($id);
        
        // Cek jika user berubah, kembalikan role user lama jika perlu (opsional, saat ini kita biarkan saja)
        // Update data pengurus
        $pengurus->update($request->all());

        // Pastikan role user baru adalah 'pengurus'
        $user = User::findOrFail($request->id_user);
        if ($user->role !== 'pengurus') {
            $user->role = 'pengurus';
            $user->save();
        }

        return redirect()->route('pengurus.pengurus.index')->with('success', 'Data pengurus berhasil diperbarui!');
    }

    /**
     * Hapus data pengurus.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $pengurus = pengurus::findOrFail($id);
        $pengurus->delete();

        return redirect()->route('pengurus.pengurus.index')->with('success', 'Data pengurus berhasil dihapus!');
    }
}
