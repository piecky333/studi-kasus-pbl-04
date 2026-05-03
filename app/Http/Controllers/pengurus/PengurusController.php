<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pengurus;
use App\Models\User;
use App\Models\Divisi;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class PengurusController extends Controller
{
    public function index()
    {
        $pengurus = Pengurus::with(['user', 'divisi', 'jabatan'])->get();
        return view('pages.pengurus.pengurus.index', compact('pengurus'));
    }

    public function create()
    {
        $users = User::where('role', 'pengurus')->get();
        $divisi = Divisi::all();
        $jabatan = Jabatan::all();
        return view('pages.pengurus.pengurus.create', compact('users', 'divisi', 'jabatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:user,id_user',
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
        ]);

        Pengurus::create($request->all());

        return redirect()->route('pengurus.pengurus.index')->with('success', 'Data pengurus berhasil ditambahkan.');
    }

    public function edit(Pengurus $pengurus)
    {
        $users = User::where('role', 'pengurus')->get();
        $divisi = Divisi::all();
        $jabatan = Jabatan::all();
        return view('pages.pengurus.pengurus.edit', compact('pengurus', 'users', 'divisi', 'jabatan'));
    }

    public function update(Request $request, Pengurus $pengurus)
    {
        $request->validate([
            'id_user' => 'required|exists:user,id_user',
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
        ]);

        $pengurus->update($request->all());

        return redirect()->route('pengurus.pengurus.index')->with('success', 'Data pengurus berhasil diperbarui.');
    }

    public function destroy(Pengurus $pengurus)
    {
        $pengurus->delete();
        return redirect()->route('pengurus.pengurus.index')->with('success', 'Data pengurus berhasil dihapus.');
    }
}
