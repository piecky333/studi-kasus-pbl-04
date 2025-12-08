<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Datamahasiswa;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MahasiswaImport;

/**
 * Class DataMahasiswaController
 * 
 * Controller ini bertanggung jawab untuk mengelola data Mahasiswa.
 * Fitur mencakup operasi CRUD lengkap (Create, Read, Update, Delete)
 * serta menampilkan detail profil mahasiswa beserta relasi (prestasi, sanksi).
 * 
 * @package App\Http\Controllers\Admin
 */
class DataMahasiswaController extends Controller
{

    /**
     * Menampilkan daftar semua mahasiswa.
     * 
     * Menggunakan Eager Loading 'admin' untuk efisiensi query.
     * Data dipaginasi 10 item per halaman.
     * 
     * @return \Illuminate\View\View
     */
    /**
     * Menampilkan daftar semua mahasiswa.
     * 
     * Menggunakan Eager Loading 'admin' untuk efisiensi query.
     * Data dipaginasi 10 item per halaman.
     * Mendukung filter semester dan pengurutan nama A-Z.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Datamahasiswa::with(['admin']);

        // Filter Semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Filter NIM
        if ($request->filled('nim')) {
            $query->where('nim', 'like', $request->nim . '%');
        }

        // Sorting
        if ($request->sort == 'latest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($request->sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            // Default sort by name A-Z
            $query->orderBy('nama', 'asc');
        }

        $mahasiswa = $query->paginate(10);
        
        return view('pages.admin.datamahasiswa.index', compact('mahasiswa'));
    }

    
    /**
     * Menampilkan form untuk menambahkan data mahasiswa baru.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.admin.datamahasiswa.create');
    }

   
    /**
     * Menyimpan data mahasiswa baru ke database.
     * 
     * Melakukan validasi ketat untuk NIM dan Email (harus unik).
     * Menyimpan juga ID Admin yang melakukan input data (Audit Trail sederhana).
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswa,nim',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:mahasiswa,email',
            'semester' => 'required|integer|min:1|max:14',
        ]);

        Datamahasiswa::create([
            'id_admin' => Auth::user()->id_admin ?? null,
            'id_user' => Auth::user()->id_user ?? null,
            'nim' => $request->nim,
            'nama' => $request->nama,
            'email' => $request->email,
            'semester' => $request->semester,
        ]);

        return redirect()->route('admin.datamahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    
    /**
     * Menampilkan detail data mahasiswa tertentu.
     * 
     * Memuat relasi 'prestasi' dan 'sanksi' untuk memberikan gambaran lengkap
     * mengenai rekam jejak mahasiswa tersebut.
     * 
     * @param string $id ID Mahasiswa
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(string $id)
    {
        $mahasiswa = Datamahasiswa::with(['admin', 'prestasi', 'sanksi'])->findOrFail($id);
        return view('pages.admin.datamahasiswa.show', compact('mahasiswa'));
    }

    
    /**
     * Menampilkan form edit data mahasiswa.
     * 
     * @param string $id
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function edit(string $id)
    {
        $mahasiswa = Datamahasiswa::findOrFail($id);
        return view('pages.admin.datamahasiswa.edit', compact('mahasiswa'));
    }

    
    /**
     * Memperbarui data mahasiswa yang sudah ada.
     * 
     * Validasi unik untuk NIM dan Email mengecualikan ID mahasiswa saat ini
     * agar tidak terjadi error "sudah digunakan" pada data diri sendiri.
     * 
     * @param Request $request
     * @param Datamahasiswa $mahasiswa Model binding otomatis
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Datamahasiswa $mahasiswa) 
    {
        
        $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswa,nim,' . $mahasiswa->id_mahasiswa . ',id_mahasiswa',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:mahasiswa,email,' . $mahasiswa->id_mahasiswa . ',id_mahasiswa',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        
        $mahasiswa->update([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'email' => $request->email,
            'semester' => $request->semester,
        ]);

        return redirect()->route('admin.datamahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Menghapus data mahasiswa secara permanen.
     * 
     * @param Datamahasiswa $mahasiswa Model binding otomatis
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Datamahasiswa $mahasiswa) 
    {
        
        $mahasiswa->delete();

        return redirect()->route('admin.datamahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    /**
     * Import data mahasiswa dari file Excel.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $import = new MahasiswaImport();
            $import->import($request->file('file')->getRealPath());
            
            return redirect()->route('admin.datamahasiswa.index')->with('success', 'Data mahasiswa berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
