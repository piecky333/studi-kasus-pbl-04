<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Sanksi;
use App\Models\admin\Datamahasiswa;
use Illuminate\Http\Request;

/**
 * Class SanksiController
 * 
 * Controller ini bertanggung jawab untuk mengelola data Sanksi Mahasiswa.
 * Fitur mencakup CRUD lengkap dengan kemampuan menambahkan sanksi ke banyak mahasiswa sekaligus (Bulk Create).
 * 
 * @package App\Http\Controllers\Admin
 */
class SanksiController extends Controller
{
    /**
     * Menampilkan daftar sanksi mahasiswa.
     * 
     * Menggunakan Eager Loading 'mahasiswa' untuk efisiensi query.
     * 
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Sanksi::select('sanksi.*')
            ->join('mahasiswa', 'sanksi.id_mahasiswa', '=', 'mahasiswa.id_mahasiswa')
            ->with('mahasiswa');

        // Filter Semester
        if ($request->filled('semester')) {
            $query->where('mahasiswa.semester', $request->semester);
        }

        // Filter NIM (Starts With)
        if ($request->filled('nim')) {
            $query->where('mahasiswa.nim', 'like', $request->nim . '%');
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'za':
                    $query->orderBy('mahasiswa.nama', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('sanksi.created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('sanksi.created_at', 'asc');
                    break;
                case 'az':
                default:
                    $query->orderBy('mahasiswa.nama', 'asc');
                    break;
            }
        } else {
            // Default sorting
            $query->orderBy('mahasiswa.nama', 'asc');
        }

        $sanksi = $query->paginate(10);
            
        return view('pages.admin.sanksi.index', compact('sanksi'));
    }

    /**
     * Menampilkan form untuk menambahkan sanksi baru.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $mahasiswa = Datamahasiswa::orderBy('nama', 'asc')->get();
        return view('pages.admin.sanksi.create', compact('mahasiswa'));
    }

    /**
     * Menyimpan data sanksi baru ke database.
     * 
     * Fitur Bulk Create:
     * Menerima array 'id_mahasiswa' dari form, memungkinkan admin memberikan
     * sanksi yang sama kepada beberapa mahasiswa sekaligus dalam satu kali submit.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

        // Iterasi setiap ID Mahasiswa yang dipilih untuk membuat record sanksi terpisah.
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

    /**
     * Menampilkan form edit data sanksi.
     * 
     * @param string $id
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function edit($id)
    {
        $sanksi = Sanksi::findOrFail($id);
        $mahasiswa = Datamahasiswa::all();
        return view('pages.admin.sanksi.edit', compact('sanksi', 'mahasiswa'));
    }

    /**
     * Memperbarui data sanksi yang sudah ada.
     * 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
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

    /**
     * Menghapus data sanksi secara permanen.
     * 
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function destroy($id)
    {
        $sanksi = Sanksi::findOrFail($id);
        $sanksi->delete();

        return redirect()->route('admin.sanksi.index')->with('success', 'Data sanksi berhasil dihapus.');
    }
    
    /**
     * Menampilkan detail data sanksi.
     * 
     * @param string $id
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show($id)
    {
        $sanksi = Sanksi::with('mahasiswa')->findOrFail($id);
        return view('pages.admin.sanksi.show', compact('sanksi'));
    }
}
