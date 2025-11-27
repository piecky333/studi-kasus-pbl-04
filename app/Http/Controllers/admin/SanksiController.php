<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Sanksi;
use App\Models\admin\Datamahasiswa;
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
        $mahasiswa = Datamahasiswa::all();
        return view('pages.admin.sanksi.create', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa'   => 'required|array',
            'id_mahasiswa.*' => 'exists:mahasiswa,id_mahasiswa',
            'jenis_sanksi'   => 'required|string|max:255',
            'jenis_hukuman'  => 'required|string|max:255',
            'tanggal_sanksi' => 'nullable|date',
            'keterangan'     => 'nullable|string',
        ]);

        foreach ($request->id_mahasiswa as $id_mahasiswa) {
            Sanksi::create([
                'id_mahasiswa'   => $id_mahasiswa,
                'jenis_sanksi'   => $request->jenis_sanksi,
                'jenis_hukuman'  => $request->jenis_hukuman,
                'tanggal_sanksi' => $request->tanggal_sanksi,
                'keterangan'     => $request->keterangan,
            ]);
        }

        return redirect()->route('admin.sanksi.index')->with('success', 'Data sanksi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $sanksi = Sanksi::findOrFail($id);
        $mahasiswa = Datamahasiswa::all();
        return view('pages.admin.sanksi.edit', compact('sanksi', 'mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'jenis_sanksi' => 'required|string|max:255',
            'jenis_hukuman' => 'required|string|max:255',
            'tanggal_sanksi' => 'nullable|date',
            'keterangan'     => 'nullable|string',
        ]);

        $sanksi = Sanksi::findOrFail($id);
        $sanksi->update($request->only(['id_mahasiswa', 'jenis_sanksi', 'jenis_hukuman', 'tanggal_sanksi', 'keterangan']));

        return redirect()->route('admin.sanksi.index')->with('success', 'Data sanksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sanksi = Sanksi::findOrFail($id);
        $sanksi->delete();

        return redirect()->route('admin.sanksi.index')->with('success', 'Data sanksi berhasil dihapus.');
    }
}
