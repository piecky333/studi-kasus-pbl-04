<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\laporan\Pengaduan;

class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduan = Pengaduan::orderBy('created_at', 'desc')->get();
       return view('public.pengaduan.index', compact('pengaduan'));

    }

    public function create()
    {
        return view('public.pengaduan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer',
            'tanggal_pengaduan' => 'required|date',
            'jenis_kasus' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        Pengaduan::create([
            'id_user' => $request->id_user,
            'tanggal_pengaduan' => $request->tanggal_pengaduan,
            'jenis_kasus' => $request->jenis_kasus,
            'deskripsi' => $request->deskripsi,
            'status_validasi' => 'menunggu',
        ]);

        return redirect('/pengaduan')->with('success', 'Pengaduan berhasil dikirim!');
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        return view('public.pengaduan.show', compact('pengaduan'));
    }

    public function destroy($id)
    {
        Pengaduan::findOrFail($id)->delete();
        return redirect('/pengaduan')->with('success', 'Pengaduan berhasil dihapus.');
    }
}
