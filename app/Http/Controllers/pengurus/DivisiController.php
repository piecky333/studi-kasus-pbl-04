<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Divisi;
use Illuminate\Support\Facades\Storage;

class DivisiController extends Controller
{
    /**
     * Tampilkan daftar semua divisi.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $divisi = Divisi::orderBy('created_at', 'desc')->get();
        return view('pages.pengurus.divisi.index', compact('divisi'));
    }

    /**
     * Tampilkan form tambah divisi.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.pengurus.divisi.create');
    }

    /**
     * Simpan data divisi baru.
     * Upload foto jika ada.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255',
            'isi_divisi' => 'nullable|string',
            'foto_divisi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $filename = null;

        if ($request->hasFile('foto_divisi')) {
            // Simpan file ke storage/uploads/divisi
            $file = $request->file('foto_divisi');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Simpan hanya nama file
            $file->storeAs('uploads/divisi', $filename, 'public');
        }

        Divisi::create([
            'nama_divisi' => $request->nama_divisi,
            'isi_divisi' => $request->isi_divisi,
            'foto_divisi' => $filename, // hanya nama file
        ]);

        return redirect()->route('pengurus.divisi.index')
            ->with('success', 'Divisi berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit divisi.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $divisi = Divisi::findOrFail($id);
        return view('pages.pengurus.divisi.edit', compact('divisi'));
    }

    /**
     * Perbarui data divisi.
     * Ganti foto jika ada upload baru.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $divisi = Divisi::findOrFail($id);

        $request->validate([
            'nama_divisi' => 'required|string|max:255',
            'isi_divisi' => 'nullable|string',
            'foto_divisi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $filename = $divisi->foto_divisi;

        if ($request->hasFile('foto_divisi')) {

            // Hapus foto lama jika ada
            if ($divisi->foto_divisi && Storage::disk('public')->exists('uploads/divisi/' . $divisi->foto_divisi)) {
                Storage::disk('public')->delete('uploads/divisi/' . $divisi->foto_divisi);
            }

            // Upload baru
            $file = $request->file('foto_divisi');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Simpan hanya nama file
            $file->storeAs('uploads/divisi', $filename, 'public');
        }

        $divisi->update([
            'nama_divisi' => $request->nama_divisi,
            'isi_divisi' => $request->isi_divisi,
            'foto_divisi' => $filename, // tetap nama file
        ]);

        return redirect()->route('pengurus.divisi.index')
            ->with('success', 'Divisi berhasil diperbarui!');
    }

    /**
     * Hapus data divisi.
     * Hapus file foto terkait dari storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $divisi = Divisi::findOrFail($id);

        // Hapus foto jika ada
        if ($divisi->foto_divisi && Storage::disk('public')->exists('uploads/divisi/' . $divisi->foto_divisi)) {
            Storage::disk('public')->delete('uploads/divisi/' . $divisi->foto_divisi);
        }

        $divisi->delete();

        return redirect()->route('pengurus.divisi.index')
            ->with('success', 'Divisi berhasil dihapus!');
    }
}
