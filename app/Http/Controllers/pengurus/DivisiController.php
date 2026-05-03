<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index()
    {
        $divisi = Divisi::orderBy('created_at', 'desc')->get();
        return view('pages.pengurus.divisi.index', compact('divisi'));
    }

    public function create()
    {
        return view('pages.pengurus.divisi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255|unique:divisi,nama_divisi',
        ]);

        Divisi::create($request->all());

        return redirect()->route('pengurus.divisi.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function show(Divisi $divisi)
    {
        return view('pages.pengurus.divisi.show', compact('divisi'));
    }

    public function edit(Divisi $divisi)
    {
        return view('pages.pengurus.divisi.edit', compact('divisi'));
    }

    public function update(Request $request, Divisi $divisi)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255|unique:divisi,nama_divisi,' . $divisi->id_divisi . ',id_divisi',
        ]);

        $divisi->update($request->all());

        return redirect()->route('pengurus.divisi.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Divisi $divisi)
    {
        $divisi->delete();
        return redirect()->route('pengurus.divisi.index')->with('success', 'Divisi berhasil dihapus.');
    }
}
