<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Datamahasiswa;
use Illuminate\Support\Facades\Auth;

class DataMahasiswaController extends Controller
{

    public function index()
    {
        $mahasiswa = Datamahasiswa::with(['admin'])->latest()->paginate(10); 
        
        return view('pages.admin.datamahasiswa.index', compact('mahasiswa'));
    }

    
    public function create()
    {
        return view('pages.admin.datamahasiswa.create');
    }

   
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

    
    public function show(string $id)
    {
        $mahasiswa = Datamahasiswa::with(['admin', 'prestasi', 'sanksi'])->findOrFail($id);
        return view('pages.admin.datamahasiswa.show', compact('mahasiswa'));
    }

    
    public function edit(string $id)
    {
        $mahasiswa = Datamahasiswa::findOrFail($id);
        return view('pages.admin.datamahasiswa.edit', compact('mahasiswa'));
    }

    
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

        public function destroy(Datamahasiswa $mahasiswa) 
    {
        
        $mahasiswa->delete();

        return redirect()->route('admin.datamahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
