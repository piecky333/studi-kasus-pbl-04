<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::all();
        return view('pages.pengurus.jabatan.index', compact('jabatan'));
    }

    public function create()
    {
        return view('pages.pengurus.jabatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
        ]);

        Jabatan::create($request->all());

        return redirect()->route('pengurus.jabatan.index')->with('success', 'Jabatan berhasil dibuat.');
    }

    public function edit(Jabatan $jabatan)
    {
        return view('pages.pengurus.jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
        ]);

        $jabatan->update($request->all());

        return redirect()->route('pengurus.jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();
        return redirect()->route('pengurus.jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
