<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Spk\KeputusanDetailController;
use App\Models\alternatif;
use App\Models\admin\Datamahasiswa; // Import Model Datamahasiswa
use App\Models\kriteria;
use App\Models\penilaian;
use App\Models\hasilakhir;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // Import untuk validasi unik yang diabaikan

/**
 * Class AlternatifController
 * 
 * Controller ini bertanggung jawab untuk mengelola operasi CRUD (Create, Read, Update, Delete)
 * untuk entitas Alternatif dalam konteks sebuah Keputusan SPK.
 * 
 * Controller ini mewarisi KeputusanDetailController untuk memastikan bahwa setiap operasi
 * yang dilakukan selalu berada dalam lingkup (scope) Keputusan yang valid ($this->idKeputusan).
 * 
 * @package App\Http\Controllers\Spk
 */
class AlternatifController extends KeputusanDetailController
{
    /**
     * Constructor.
     * 
     * Memanggil constructor parent untuk memuat data Keputusan berdasarkan ID yang ada di route.
     * Ini menjamin properti $this->keputusan dan $this->idKeputusan tersedia untuk semua method.
     * 
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        // Memastikan Keputusan (parent) dimuat sebelum method lain dijalankan
        parent::__construct($request);
    }

    /**
     * Menampilkan daftar Alternatif untuk Keputusan saat ini.
     * 
     * Mengambil semua data alternatif yang terkait dengan ID Keputusan yang sedang aktif,
     * diurutkan berdasarkan nama alternatif secara ascending.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $alternatifList = alternatif::where('id_keputusan', $this->idKeputusan)
            ->with(['penilaian', 'mahasiswa']) // Load relasi penilaian & mahasiswa
            ->orderBy('nama_alternatif', 'asc')
            ->get();
        
        return view('pages.admin.spk.alternatif.index', [
            'idKeputusan' => $this->idKeputusan, 
            'keputusan' => $this->keputusan, 
            'alternatifList' => $alternatifList,
            'pageTitle' => 'Manajemen Alternatif'
        ]);
    }

    /**
     * Menampilkan form untuk menambahkan Alternatif baru.
     * 
     * Method ini menyiapkan data mahasiswa yang belum terdaftar sebagai alternatif
     * dalam keputusan ini, untuk menghindari duplikasi data.
     * 
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $query = Datamahasiswa::select('id_mahasiswa', 'nim', 'nama', 'semester');

        // Filter Semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Filter Prestasi (Punya Prestasi / Tidak)
        if ($request->filled('filter_prestasi')) {
            if ($request->filter_prestasi == 'ada') {
                $query->whereHas('prestasi');
            } elseif ($request->filter_prestasi == 'tidak_ada') {
                $query->whereDoesntHave('prestasi');
            }
        }

        // Filter Sanksi (Punya Sanksi / Tidak)
        if ($request->filled('filter_sanksi')) {
             if ($request->filter_sanksi == 'ada') {
                $query->whereHas('sanksi');
            } elseif ($request->filter_sanksi == 'tidak_ada') {
                $query->whereDoesntHave('sanksi');
            }
        }

        $mahasiswa = $query->orderBy('nama', 'asc')->get();
        
        // Mengambil daftar ID Mahasiswa yang SUDAH menjadi alternatif di keputusan ini.
        // Data ini akan digunakan di view untuk menonaktifkan/menyembunyikan opsi mahasiswa tersebut.
        $existingMahasiswaIds = alternatif::where('id_keputusan', $this->idKeputusan)
                                          ->pluck('id_mahasiswa')
                                          ->toArray();

        return view('pages.admin.spk.alternatif.create', [
            'idKeputusan' => $this->idKeputusan,
            'keputusan' => $this->keputusan,
            'mahasiswaList' => $mahasiswa,
            'existingMahasiswaIds' => $existingMahasiswaIds,
            'pageTitle' => 'Tambah Alternatif Baru'
        ]);
    }

    /**
     * Menyimpan satu atau lebih Alternatif baru ke database.
     * 
     * Proses ini mencakup:
     * 1. Validasi input (memastikan ID Mahasiswa valid).
     * 2. Pengecekan duplikasi manual (double-check selain validasi UI).
     * 3. Pembuatan record Alternatif.
     * 4. Inisiasi nilai default (0) untuk setiap Kriteria yang ada, agar matriks penilaian lengkap.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mahasiswa' => 'required|array',
            'id_mahasiswa.*' => 'exists:mahasiswa,id_mahasiswa', // Pastikan ID valid di tabel mahasiswa
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $countBerhasil = 0;
        $countGagal = 0;

        foreach ($validated['id_mahasiswa'] as $idMahasiswa) {
            // Validasi Logis: Cek apakah mahasiswa ini sudah ada di keputusan ini.
            // Meskipun UI sudah memfilter, validasi backend tetap diperlukan untuk integritas data.
            $exists = alternatif::where('id_keputusan', $this->idKeputusan)
                                ->where('id_mahasiswa', $idMahasiswa)
                                ->exists();
            
            if ($exists) {
                $countGagal++;
                continue;
            }

            $mhs = Datamahasiswa::find($idMahasiswa);
            if (!$mhs) continue;

            // Buat Alternatif baru
            $alternatifBaru = alternatif::create([
                'id_keputusan' => $this->idKeputusan,
                'id_mahasiswa' => $mhs->id_mahasiswa,
                'nama_alternatif' => $mhs->nama, // Gunakan nama dari data mahasiswa
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Inisiasi Data Penilaian:
            // Setiap kali alternatif baru dibuat, maka sistem harus membuat record penilaian kosong (nilai 0)
            // untuk setiap kriteria yang ada di keputusan ini. Ini memudahkan proses input nilai nantinya.
            $kriteriaList = kriteria::where('id_keputusan', $this->idKeputusan)->get();
            foreach ($kriteriaList as $kriteria) {
                penilaian::create([
                    'id_alternatif' => $alternatifBaru->id_alternatif,
                    'id_kriteria' => $kriteria->id_kriteria,
                    'nilai' => 0, 
                ]);
            }
            $countBerhasil++;
        }

        $message = "$countBerhasil alternatif berhasil ditambahkan.";
        if ($countGagal > 0) {
            $message .= " ($countGagal dilewati karena sudah ada)";
        }

        return redirect()->route('admin.spk.alternatif.index', $this->idKeputusan)
                         ->with('success', $message);
    }

    /**
     * Menampilkan form edit untuk Alternatif tertentu.
     * 
     * @param mixed $idKeputusan Parameter dari route (tidak digunakan langsung karena ada $this->idKeputusan)
     * @param int $idAlternatif ID Alternatif yang akan diedit
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika alternatif tidak ditemukan atau tidak milik keputusan ini
     */
    public function edit($idKeputusan, $idAlternatif)
    {
        $alternatif = alternatif::where('id_keputusan', $this->idKeputusan)
                                     ->findOrFail($idAlternatif);

        return view('pages.admin.spk.alternatif.edit', [
            'idKeputusan' => $this->idKeputusan,
            'keputusan' => $this->keputusan,
            'alternatif' => $alternatif,
            'pageTitle' => 'Edit Alternatif'
        ]);
    }

    /**
     * Memperbarui data Alternatif di database.
     * 
     * @param Request $request
     * @param mixed $idKeputusan
     * @param int $idAlternatif
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(Request $request, $idKeputusan, $idAlternatif)
    {
        $alternatif = alternatif::where('id_keputusan', $this->idKeputusan)
                                     ->findOrFail($idAlternatif);
        
        // 1. Validasi Input, termasuk kolom baru dan memastikan ID Mahasiswa unik (kecuali untuk dirinya sendiri)
        $validated = $request->validate([
            'nama_alternatif' => 'required|string|max:255',
            'id_mahasiswa' => [
                'nullable', 
                'string', 
                'max:50',
                // Validasi Unik Bersyarat:
                // Memastikan ID Mahasiswa unik dalam scope keputusan ini ($this->idKeputusan),
                // TETAPI mengecualikan record yang sedang diedit ($alternatif->id_alternatif) agar tidak dianggap duplikat diri sendiri.
                Rule::unique('alternatif')->where(function ($query) use ($alternatif) {
                    return $query->where('id_keputusan', $this->idKeputusan)
                                 ->where('id_alternatif', '!=', $alternatif->id_alternatif);
                }),
            ],
            'keterangan' => 'nullable|string|max:1000',
        ]);
        
        // 2. Update model dengan semua data yang tervalidasi
        $alternatif->update($validated);

        return redirect()->route('admin.spk.alternatif.index', $this->idKeputusan)
                         ->with('success', 'Alternatif "' . $alternatif->nama_alternatif . '" berhasil diperbarui.');
    }

    /**
     * Menghapus beberapa Alternatif sekaligus (Bulk Delete).
     * 
     * Menggunakan Database Transaction untuk memastikan atomicity:
     * Entah semua data (alternatif, penilaian, hasil akhir) terhapus, atau tidak sama sekali jika terjadi error.
     * 
     * @param Request $request
     * @param mixed $idKeputusan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkDestroy(Request $request, $idKeputusan)
    {
        $request->validate([
            'selected_alternatif' => 'required|array',
            'selected_alternatif.*' => 'exists:alternatif,id_alternatif',
        ]);

        DB::beginTransaction();
        try {
            $ids = $request->selected_alternatif;
            
            // Hapus data terkait
            penilaian::whereIn('id_alternatif', $ids)->delete();
            hasilakhir::whereIn('id_alternatif', $ids)->delete();
            
            // Hapus alternatif
            alternatif::whereIn('id_alternatif', $ids)->delete();

            DB::commit();

            return redirect()->route('admin.spk.alternatif.index', $this->idKeputusan)
                             ->with('success', count($ids) . ' alternatif berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.spk.alternatif.index', $this->idKeputusan)
                             ->with('error', 'Gagal menghapus alternatif: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus satu Alternatif beserta data terkaitnya.
     * 
     * Data yang dihapus meliputi:
     * 1. Data Penilaian terkait alternatif ini.
     * 2. Data Hasil Akhir terkait alternatif ini.
     * 3. Record Alternatif itu sendiri.
     * 
     * @param mixed $idKeputusan
     * @param int $idAlternatif
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($idKeputusan, $idAlternatif)
    {
        DB::beginTransaction();
        try {
            $alternatif = alternatif::where('id_keputusan', $this->idKeputusan)
                                     ->findOrFail($idAlternatif);
            $nama = $alternatif->nama_alternatif;

            penilaian::where('id_alternatif', $idAlternatif)->delete();
            hasilakhir::where('id_alternatif', $idAlternatif)->delete(); 
            
            $alternatif->delete();

            DB::commit();

            return redirect()->route('admin.spk.alternatif.index', $this->idKeputusan)
                             ->with('success', 'Alternatif "' . $nama . '" berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.spk.alternatif.index', $this->idKeputusan)
                             ->with('error', 'Gagal menghapus alternatif: ' . $e->getMessage());
        }
    }
} 