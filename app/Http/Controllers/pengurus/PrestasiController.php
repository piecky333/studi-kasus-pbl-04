<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\DataMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Prestasi::with(['mahasiswa']);

        if ($request->filled('nim')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('nim', 'like', $request->nim . '%');
            });
        }

        $prestasi = $query->paginate(10);
        return view('pages.pengurus.prestasi.index', compact('prestasi'));
    }

    public function create()
    {
        $mahasiswa = DataMahasiswa::orderBy('nama', 'asc')->get();
        return view('pages.pengurus.prestasi.create', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa'   => 'required|array',
            'judul_prestasi' => 'required|string|max:255',
            'jenis_prestasi' => 'required|in:Akademik,Non-Akademik',
            'tingkat'        => 'required|string|max:255',
            'juara'          => 'required|string|max:255',
            'tanggal'        => 'required|date',
        ], [
            'id_mahasiswa.required'   => 'Pilih minimal satu mahasiswa.',
            'id_mahasiswa.array'      => 'Data mahasiswa tidak valid.',
            'judul_prestasi.required' => 'Judul prestasi wajib diisi.',
            'judul_prestasi.max'      => 'Judul prestasi maksimal 255 karakter.',
            'jenis_prestasi.required' => 'Jenis prestasi wajib dipilih.',
            'jenis_prestasi.in'       => 'Jenis prestasi harus Akademik atau Non-Akademik.',
            'tingkat.required'        => 'Tingkat prestasi wajib dipilih.',
            'juara.required'          => 'Juara / peringkat wajib dipilih.',
            'tanggal.required'        => 'Tanggal prestasi wajib diisi.',
            'tanggal.date'            => 'Format tanggal tidak valid.',
        ]);

        foreach ($request->id_mahasiswa as $id_mahasiswa) {
            Prestasi::create([
                'id_mahasiswa'   => $id_mahasiswa,
                'nama_kegiatan'  => $request->judul_prestasi,
                'jenis_prestasi' => $request->jenis_prestasi,
                'tingkat_prestasi' => $request->tingkat,
                'juara'          => $request->juara,
                'tahun'          => date('Y', strtotime($request->tanggal)),
                'status_validasi' => 'disetujui',
            ]);
        }

        return redirect()->route('pengurus.prestasi.index')->with('success', 'Data prestasi berhasil ditambahkan.');
    }

    public function show(Prestasi $prestasi)
    {
        $prestasi->load('mahasiswa');
        return view('pages.pengurus.prestasi.show', compact('prestasi'));
    }

    public function edit(Prestasi $prestasi)
    {
        $prestasi->load('mahasiswa');
        return view('pages.pengurus.prestasi.edit', compact('prestasi'));
    }

    public function update(Request $request, Prestasi $prestasi)
    {
        $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
            'status_validasi'  => 'required|in:menunggu,disetujui,ditolak',
        ]);

        $prestasi->update($request->all());

        return redirect()->route('pengurus.prestasi.index')->with('success', 'Data prestasi berhasil diperbarui.');
    }

    public function destroy(Prestasi $prestasi)
    {
        $prestasi->delete();
        return redirect()->route('pengurus.prestasi.index')->with('success', 'Data prestasi berhasil dihapus.');
    }

    public function cariMahasiswa(Request $request)
    {
        $nim = $request->query('nim');
        $mahasiswa = DataMahasiswa::where('nim', $nim)->first();

        if ($mahasiswa) {
            return response()->json([
                'success' => true,
                'mahasiswa' => $mahasiswa
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Mahasiswa tidak ditemukan'
        ]);
    }
}
