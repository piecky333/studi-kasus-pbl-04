<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pengurus;
use App\Models\User;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\DataMahasiswa;
use Illuminate\Http\Request;

class PengurusController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengurus::with(['user.mahasiswa', 'divisi', 'jabatan']);

        if ($request->filled('id_divisi')) {
            $query->where('id_divisi', $request->id_divisi);
        }

        if ($request->filled('semester')) {
            $query->whereHas('user.mahasiswa', function($q) use ($request) {
                $q->where('semester', $request->semester);
            });
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('user', function($uq) use ($request) {
                    $uq->where('nama', 'like', '%' . $request->search . '%')
                      ->orWhereHas('mahasiswa', function($mq) use ($request) {
                          $mq->where('nama', 'like', '%' . $request->search . '%')
                             ->orWhere('nim', 'like', '%' . $request->search . '%');
                      });
                })
                ->orWhereHas('divisi', function($dq) use ($request) {
                    $dq->where('nama_divisi', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('jabatan', function($jq) use ($request) {
                    $jq->where('nama_jabatan', 'like', '%' . $request->search . '%');
                });
            });
        }

        $pengurus = $query->paginate(10);
        $divisi = Divisi::all();
        $semesters = range(1, 8);

        return view('pages.pengurus.pengurus.index', compact('pengurus', 'divisi', 'semesters'));
    }

    public function create()
    {
        $mahasiswa = DataMahasiswa::orderBy('nama', 'asc')->get();
        $divisi = Divisi::all();
        $jabatan = Jabatan::all();
        return view('pages.pengurus.pengurus.create', compact('mahasiswa', 'divisi', 'jabatan'));
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

    public function edit(Pengurus $penguru)
    {
        $pengurus = $penguru;
        $mahasiswa = DataMahasiswa::orderBy('nama', 'asc')->get();
        $divisi = Divisi::all();
        $jabatan = Jabatan::all();
        return view('pages.pengurus.pengurus.edit', compact('pengurus', 'mahasiswa', 'divisi', 'jabatan'));
    }

    public function update(Request $request, Pengurus $penguru)
    {
        $pengurus = $penguru;
        $request->validate([
            'id_user' => 'required|exists:user,id_user',
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
        ]);

        $pengurus->update($request->all());

        return redirect()->route('pengurus.pengurus.index')->with('success', 'Data pengurus berhasil diperbarui.');
    }

    public function destroy(Pengurus $penguru)
    {
        $pengurus = $penguru;
        $pengurus->delete();
        return redirect()->route('pengurus.pengurus.index')->with('success', 'Data pengurus berhasil dihapus.');
    }
}
