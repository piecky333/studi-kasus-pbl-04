<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Sanksi;
use App\Models\Admin\Mahasiswa;
use Illuminate\Http\Request;

class SanksiController extends Controller
{
    public function index()
    {
        $sanksi = Sanksi::with('mahasiswa')->latest()->paginate(10);
        return view('pages.admin.sanksi.index', compact('sanksi'));
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::all();
        return view('pages.admin.sanksi.create', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'jenis_sanksi' => 'required|string|max:255',
            'tanggal_sanksi' => 'nullable|date',
        ]);

        Sanksi::create($request->only(['id_mahasiswa', 'jenis_sanksi', 'tanggal_sanksi']));

        return redirect()->route('admin.sanksi.index')->with('success', 'Data sanksi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $sanksi = Sanksi::findOrFail($id);
        $mahasiswa = Mahasiswa::all();
        return view('pages.admin.sanksi.edit', compact('sanksi', 'mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'jenis_sanksi' => 'required|string|max:255',
            'tanggal_sanksi' => 'nullable|date',
        ]);

        $sanksi = Sanksi::findOrFail($id);
        $sanksi->update($request->only(['id_mahasiswa', 'jenis_sanksi', 'tanggal_sanksi']));

        return redirect()->route('admin.sanksi.index')->with('success', 'Data sanksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sanksi = Sanksi::findOrFail($id);
        $sanksi->delete();

        return redirect()->route('admin.sanksi.index')->with('success', 'Data sanksi berhasil dihapus.');
    }
}
