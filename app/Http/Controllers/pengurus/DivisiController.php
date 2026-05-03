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
            'isi_divisi'  => 'nullable|string',
            'foto_divisi' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ], [
            'nama_divisi.required' => 'Nama divisi wajib diisi.',
            'nama_divisi.unique'   => 'Nama divisi sudah ada, gunakan nama lain.',
            'foto_divisi.image'    => 'File harus berupa gambar.',
            'foto_divisi.max'      => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = $request->only(['nama_divisi', 'isi_divisi']);

        if ($request->hasFile('foto_divisi')) {
            $data['foto_divisi'] = $request->file('foto_divisi')->store('divisi', 'public');
        }

        Divisi::create($data);

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
            'isi_divisi'  => 'nullable|string',
            'foto_divisi' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ], [
            'nama_divisi.required' => 'Nama divisi wajib diisi.',
            'nama_divisi.unique'   => 'Nama divisi sudah ada, gunakan nama lain.',
            'foto_divisi.image'    => 'File harus berupa gambar.',
            'foto_divisi.max'      => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = $request->only(['nama_divisi', 'isi_divisi']);

        if ($request->hasFile('foto_divisi')) {
            // Hapus foto lama jika ada
            if ($divisi->foto_divisi) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($divisi->foto_divisi);
            }
            $data['foto_divisi'] = $request->file('foto_divisi')->store('divisi', 'public');
        }

        $divisi->update($data);

        return redirect()->route('pengurus.divisi.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Divisi $divisi)
    {
        $divisi->delete();
        return redirect()->route('pengurus.divisi.index')->with('success', 'Divisi berhasil dihapus.');
    }
}
