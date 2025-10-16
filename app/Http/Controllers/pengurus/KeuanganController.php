<?php

namespace App\Http\Controllers\pengurus;

use App\Http\Controllers\Controller;
use App\Models\admin\Keuangan;
use App\Models\admin\Divisi;
use App\Models\admin\Pengurus;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index()
    {
        $keuangan = Keuangan::with(['divisi', 'pengurus'])->latest()->get();
        return view('pages.pengurus.keuangan.index', compact('keuangan'));
    }

    public function create()
    {
        $divisi = Divisi::all();
        $pengurus = Pengurus::all();
        return view('pages.pengurus.keuangan.create', compact('divisi', 'pengurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pengurus' => 'required|exists:pengurus,id_pengurus',
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'jumlah_iuran' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer,qris',
        ]);

        Keuangan::create([
            'id_pengurus' => $request->id_pengurus,
            'id_divisi' => $request->id_divisi,
            'jumlah_iuran' => $request->jumlah_iuran,
            'tanggal_bayar' => now(),
            'deadline' => now()->addDays(7),
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_pembayaran' => 'belum',
        ]);

        return redirect()->route('pengurus.keuangan.index')->with('success', 'Data keuangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $keuangan = Keuangan::findOrFail($id);
        $divisi = Divisi::all();
        $pengurus = Pengurus::all();
        return view('pages.pengurus.keuangan.edit', compact('keuangan', 'divisi', 'pengurus'));
    }

    public function update(Request $request, $id)
    {
        $keuangan = Keuangan::findOrFail($id);

        $request->validate([
            'jumlah_iuran' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:belum,proses,lunas',
        ]);

        $keuangan->update([
            'jumlah_iuran' => $request->jumlah_iuran,
            'status_pembayaran' => $request->status_pembayaran,
        ]);

        return redirect()->route('pengurus.keuangan.index')->with('success', 'Data keuangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $keuangan = Keuangan::findOrFail($id);
        $keuangan->delete();

        return redirect()->route('pengurus.keuangan.index')->with('success', 'Data keuangan berhasil dihapus.');
    }
}
