<?php

namespace App\Http\Controllers\pengurus;

use App\Http\Controllers\Controller;
use App\Models\admin\pengurus;
use App\Models\admin\divisi;
use App\Models\User;
use Illuminate\Http\Request;

class PengurusController extends Controller
{
    public function index()
    {
        $pengurus = pengurus::with(['divisi', 'user'])->latest()->get();
        return view('pages.pengurus.pengurus.index', compact('pengurus'));
    }

    public function create()
    {
        $divisi = divisi::all();
        $users = User::all();
        return view('pages.pengurus.pengurus.create', compact('divisi', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'id_user' => 'required|exists:user,id_user',
            'posisi_jabatan' => 'required|string|max:255',
        ]);

        pengurus::create($request->all());

        return redirect()->route('pengurus.pengurus.index')->with('success', 'Pengurus berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $pengurus = pengurus::findOrFail($id);
        $divisi = divisi::all();
        $users = User::all();
        return view('pages.pengurus.pengurus.edit', compact('pengurus', 'divisi', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'id_user' => 'required|exists:user,id_user',
            'posisi_jabatan' => 'required|string|max:255',
        ]);

        $pengurus = pengurus::findOrFail($id);
        $pengurus->update($request->all());

        return redirect()->route('pengurus.pengurus.index')->with('success', 'Data pengurus berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pengurus = pengurus::findOrFail($id);
        $pengurus->delete();

        return redirect()->route('pengurus.pengurus.index')->with('success', 'Data pengurus berhasil dihapus!');
    }
}
