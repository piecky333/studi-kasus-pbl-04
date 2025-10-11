<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\laporan\laporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    /**
     * 🧾 Menampilkan semua laporan user (halaman utama)
     */
    public function index()
    {
        // Jika hanya ingin menampilkan laporan milik user yang login:
        $laporan = laporan::where('id_user', Auth::id())->latest()->get();

        return view('laporan.index', compact('laporan'));
    }

    /**
     * 📝 Menampilkan form tambah laporan
     */
    public function create()
    {
        return view('laporan.create');
    }

    /**
     * 💾 Menyimpan laporan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_laporan' => 'required|string|max:150',
            'isi_laporan' => 'required|string',
            'bukti_laporan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'judul_laporan' => $request->judul_laporan,
            'isi_laporan' => $request->isi_laporan,
            'tanggal_lapor' => now(),
            'id_user' => Auth::id(),
        ];

        // Upload bukti jika ada
        if ($request->hasFile('bukti_laporan')) {
            $data['bukti_laporan'] = $request->file('bukti_laporan')->store('bukti_laporan', 'public');
        }

        Laporan::create($data);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dikirim.');
    }

    /**
     * ✏️ Menampilkan form edit laporan
     */
    public function edit($id)
    {
        $laporan = LaporanController::findOrFail($id);

        // Pastikan hanya pemilik laporan yang bisa edit
        if ($laporan->id_user != Auth::id()) {
            abort(403);
        }

        return view('laporan.edit', compact('laporan'));
    }

    /**
     * 🔄 Menyimpan perubahan laporan
     */
    public function update(Request $request, $id)
    {
        $laporan = LaporanController::findOrFail($id);

        if ($laporan->id_user != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'judul_laporan' => 'required|string|max:150',
            'isi_laporan' => 'required|string',
            'bukti_laporan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['judul_laporan', 'isi_laporan']);

        // Ganti file jika diupload ulang
        if ($request->hasFile('bukti_laporan')) {
            if ($laporan->bukti_laporan && Storage::disk('public')->exists($laporan->bukti_laporan)) {
                Storage::disk('public')->delete($laporan->bukti_laporan);
            }
            $data['bukti_laporan'] = $request->file('bukti_laporan')->store('bukti_laporan', 'public');
        }

        $laporan->update($data);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    /**
     * ❌ Menghapus laporan
     */
    public function destroy($id)
    {
        $laporan = LaporanController::findOrFail($id);

        if ($laporan->id_user != Auth::id()) {
            abort(403);
        }

        if ($laporan->bukti_laporan && Storage::disk('public')->exists($laporan->bukti_laporan)) {
            Storage::disk('public')->delete($laporan->bukti_laporan);
        }

        $laporan->delete();

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }

    /**
     * 👁️ (Opsional) Menampilkan detail laporan
     */
    public function show($id)
    {
        $laporan = laporan::findOrFail($id);

        if ($laporan->id_user != Auth::id()) {
            abort(403);
        }

        return view('laporan.show', compact('laporan'));
    }
}
