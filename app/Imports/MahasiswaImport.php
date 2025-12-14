<?php

namespace App\Imports;

use App\Models\User;
use App\Models\admin\Datamahasiswa; 
use App\Models\admin\prestasi; 
use App\Models\admin\sanksi;  
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MahasiswaImport
{
    /**
     * ID Admin diperlukan untuk mengasosiasikan data yang diimport.
     * @var int|null
     */
    protected $idAdmin;

    /**
     * Konstruktor.
     *
     * @param int|null $idAdmin ID admin yang melakukan import.
     */
    public function __construct($idAdmin = null)
    {
        $this->idAdmin = $idAdmin;
    }

    /**
     * Eksekusi proses import.
     *
     * Metode ini membaca file Excel, memetakan header secara fleksibel,
     * dan membuat/memperbarui record User, Mahasiswa, Prestasi, dan Sanksi.
     *
     * @param string $filePath Path absolut ke file Excel yang diunggah.
     * @throws \Exception Jika import gagal pada titik tertentu.
     * @return void
     */
    public function import($filePath)
    {
        Log::info('Starting import process for file: ' . $filePath);

        DB::beginTransaction(); // Mulai Transaksi Database
        try {
            // Muat Spreadsheet
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            Log::info('Total rows found: ' . count($rows));

            // =========================================================================
            // 1. PEMETAAN HEADER DINAMIS (DYNAMIC HEADER MAPPING)
            // =========================================================================
            // [LOGIC] Membaca baris pertama untuk menentukan indeks kolom secara dinamis.
            // [FEATURE] Flexibilitas Kolom: Allows 'nim', 'nomor induk', etc. to be in any column index.
            $headerRow = array_shift($rows);
            $headerMap = [];
            foreach ($headerRow as $index => $colName) {
                if ($colName) {
                    $headerMap[strtolower(trim($colName))] = $index;
                }
            }
            Log::info('Header Map: ' . json_encode($headerMap));

            // Helper: Cari nilai dari baris menggunakan beberapa kemungkinan kata kunci header.
            $getValue = function($row, $possibleHeaders) use ($headerMap) {
                foreach ($possibleHeaders as $header) {
                    if (isset($headerMap[$header]) && isset($row[$headerMap[$header]])) {
                        return $row[$headerMap[$header]];
                    }
                }
                return null;
            };

            // Penghitung untuk statistik
            $countNew = 0;
            $countUpdated = 0;

            // =========================================================================
            // 2. LOOP PEMROSESAN BARIS (ROW PROCESSING)
            // =========================================================================
            foreach ($rows as $index => $row) {
                Log::info("Processing row " . ($index + 2));

                // --- Ekstrak Data Dasar Mahasiswa ---
                $nim = $getValue($row, ['nim', 'nomor induk mahasiswa']);
                $nama = $getValue($row, ['nama', 'nama mahasiswa', 'nama lengkap']);
                $email = $getValue($row, ['email', 'e-mail', 'alamat email']);
                $semester = $getValue($row, ['semester']) ?? 1;
                $ipk = $getValue($row, ['ipk', 'indeks prestasi', 'grade']); 
                
                // Validasi IPK: Pastikan rentang 0.00 - 4.00
                if ($ipk !== null) {
                    $ipk = floatval($ipk);
                    if ($ipk > 4.00) {
                        $ipk = 4.00;
                    } elseif ($ipk < 0) {
                        $ipk = 0.00;
                    }
                }

                // --- Ekstrak Data Prestasi ---
                $prestasiNama = $getValue($row, ['nama kegiatan', 'kegiatan', 'prestasi', 'judul prestasi', 'nama prestasi', 'nama_kegiatan']);
                $prestasiTingkatRaw = $getValue($row, ['tingkat prestasi', 'tingkat', 'level', 'tingkat_prestasi', 'tingkatan']);
                $prestasiTahun = $getValue($row, ['tahun prestasi', 'tahun']);
                $prestasiStatus = $getValue($row, ['status validasi', 'status', 'validasi', 'status_validasi']) ?? 'menunggu';

                // --- Ekstrak Data Sanksi ---
                $sanksiTanggalRaw = $getValue($row, ['tanggal sanksi', 'tanggal', 'tanggal_sanksi']);
                $sanksiJenis = $getValue($row, ['jenis sanksi', 'sanksi', 'tipe sanksi', 'jenis_sanksi']);
                $sanksiHukuman = $getValue($row, ['jenis hukuman', 'hukuman', 'jenis_hukuman']) ?? 'Teguran'; 
                $sanksiKeteranganRaw = $getValue($row, ['keterangan', 'deskripsi', 'catatan']);
                
                $sanksiKeterangan = $sanksiKeteranganRaw ?? $sanksiJenis; // Deskripsi cadangan jika kosong

                // Parsing Tanggal Sanksi (Format Numerik Excel atau String)
                $sanksiTanggal = null;
                if (!empty($sanksiTanggalRaw)) {
                    if (is_numeric($sanksiTanggalRaw)) {
                        $sanksiTanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($sanksiTanggalRaw)->format('Y-m-d');
                    } else {
                        try {
                            $sanksiTanggal = \Carbon\Carbon::parse($sanksiTanggalRaw)->format('Y-m-d');
                        } catch (\Exception $e) {
                            $sanksiTanggal = null;
                        }
                    }
                }

                // --- Validasi Dasar Kelengkapan Data ---
                // [LOGIC] Email tidak wajib di Excel karena kita bisa generate dari Nama
                if (!$nim || !$nama) {
                    Log::warning("Row " . ($index + 2) . " skipped: Incomplete data (NIM/Nama missing).");
                    continue;
                }

                // =========================================================================
                // 3. PEMBUATAN/PEMBARUAN USER & MAHASISWA
                // =========================================================================
                
                // [LOGIC] Cari atau Buat Akun User
                // [FEATURE] Auto-Generate Email & Password
                // Email dibuat dari nama: nama.lengkap@mhs.politala.ac.id
                // Password default: NIM
                
                if ($nama) {
                    $cleanName = strtolower(trim($nama));
                    $emailPrefix = str_replace(' ', '.', $cleanName);
                    // [SECURITY] Remove special characters to prevent injection/invalid emails
                    $emailPrefix = preg_replace('/[^a-z0-9\.]/', '', $emailPrefix);
                    $generatedEmail = $emailPrefix . '@mhs.politala.ac.id';
                } else {
                    $generatedEmail = $email; // [FALLBACK] Gunakan email dari excel jika nama kosong
                }

                // [IMPORTANT] Override variable email utama dengan yang digenerate
                $email = $generatedEmail;

                $user = User::where('email', $email)->orWhere('username', $nim)->first();
                $isNewUser = false;
                
                if (!$user) {
                     $user = User::create([
                        'nama' => $nama,
                        'username' => $nim, // [NOTE] Username menggunakan NIM
                        'email' => $email,
                        'password' => Hash::make($nim), // [SECURITY] Password default adalah NIM
                        'role' => 'mahasiswa',
                    ]);
                    $isNewUser = true;
                    $countNew++;
                    Log::info("User created: " . $user->username);
                } else {
                    $countUpdated++;
                    Log::info("User exists: " . $user->username);
                }

                // [LOGIC] Cari atau Buat Profil Datamahasiswa
                // Menghubungkan User (akun login) dengan data akademik mahasiswa
                $mahasiswa = Datamahasiswa::where('id_user', $user->id_user)->first();

                if (!$mahasiswa) {
                    $mahasiswa = Datamahasiswa::create([
                        'id_user' => $user->id_user,
                        'nim' => $nim,
                        'nama' => $nama,
                        'email' => $email,
                        'semester' => $semester,
                        'ipk' => $ipk,
                        'id_admin' => $this->idAdmin, 
                    ]);
                     Log::info("Mahasiswa profile created for User ID: " . $user->id_user);
                }

                // =========================================================================
                // 4. LOGIKA IMPORT PRESTASI (OPTIONAL)
                // =========================================================================
                // [FEATURE] Multi-Row Support: Jika Mahasiswa punya banyak prestasi, 
                // cukup buat baris baru dengan NIM yang sama di Excel.
                
                // Headers: 'nama kegiatan', 'tingkat', 'tahun', 'status', 'jenis prestasi', 'deskripsi', 'juara'
                $prestasiDeskripsi = $getValue($row, ['deskripsi prestasi', 'keterangan prestasi', 'deskripsi']);
                $prestasiJenis = $getValue($row, ['jenis prestasi', 'jenis', 'kategori prestasi']); // Akademik / Non-Akademik
                $prestasiJuara = $getValue($row, ['juara', 'peringkat', 'rank']); // Juara 1, Juara 2, dll

                if ($prestasiNama || $prestasiTingkatRaw) {
                     // Mapping Level Prestasi
                     $validTingkatMap = [
                         'internasional' => 'Internasional',
                         'nasional' => 'Nasional',
                         'provinsi' => 'Provinsi',
                         'kabupaten/kota' => 'Kabupaten/Kota',
                         'kota' => 'Kabupaten/Kota',
                         'kabupaten' => 'Kabupaten/Kota',
                         'universitas' => 'Internal',
                         'internal' => 'Internal', 
                         'kampus' => 'Internal',
                         'fakultas' => 'Internal', 
                     ];
                     
                     $normalizedTingkat = strtolower(trim($prestasiTingkatRaw));
                     $finalTingkat = $validTingkatMap[$normalizedTingkat] ?? null;

                     $finalNama = $prestasiNama ?: "Prestasi Tingkat " . ($finalTingkat ?? 'Lainnya');

                     if ($finalTingkat || $prestasiNama) {
                        // Cek duplikasi
                        $exists = prestasi::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                            ->where('nama_kegiatan', $finalNama)
                            ->where('tahun', $prestasiTahun ?? date('Y'))
                            ->exists();

                        if (!$exists) {
                            // [LOGIC] Tentukan Jenis Prestasi (Default: Akademik)
                            $finalJenis = 'Akademik';
                            if ($prestasiJenis && strtolower($prestasiJenis) == 'non-akademik') {
                                $finalJenis = 'Non-Akademik';
                            } elseif ($prestasiJenis && strtolower($prestasiJenis) == 'non akademik') {
                                $finalJenis = 'Non-Akademik';
                            }

                            prestasi::create([
                                'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                                'id_admin' => $this->idAdmin,
                                'nama_kegiatan' => $finalNama,
                                'jenis_prestasi' => $finalJenis,
                                'tingkat_prestasi' => $finalTingkat ?? 'Internal', 
                                'juara' => $prestasiJuara, // [FEATURE] Added Juara Mapping
                                'tahun' => $prestasiTahun ?? date('Y'),
                                'status_validasi' => strtolower($prestasiStatus) == 'disetujui' ? 'disetujui' : 'menunggu', 
                                'deskripsi' => $prestasiDeskripsi ?? 'Dikirim dari Excel',
                                'bukti_path' => null, 
                            ]);
                            Log::info("Prestasi added for NIM: " . $nim);
                        }
                     }
                }

                // =========================================================================
                // 5. LOGIKA IMPORT SANKSI (OPTIONAL)
                // =========================================================================
                // Headers: 'jenis sanksi', 'hukuman', 'tanggal', 'keterangan'
                if ($sanksiJenis || $sanksiHukuman) {
                    
                    // Cek duplikasi untuk sanksi juga
                    $sanksiExists = sanksi::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                        ->where('jenis_sanksi', $sanksiJenis)
                        ->where('tanggal_sanksi', $sanksiTanggal ?? date('Y-m-d'))
                        ->exists();

                    if (!$sanksiExists) {
                        sanksi::create([
                            'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                            'tanggal_sanksi' => $sanksiTanggal ?? date('Y-m-d'),
                            'jenis_sanksi' => $sanksiJenis ?? 'Ringan',
                            'jenis_hukuman' => $sanksiHukuman ?? 'Teguran Lisan',
                            'keterangan' => $sanksiKeterangan ?? 'Dikirim dari Excel',
                            // 'file_pendukung' => null (default)
                        ]);
                        Log::info("Sanksi added for NIM: " . $nim);
                    }
                }
            } // Akhir Loop

            DB::commit(); // Simpan Transaksi (Commit)
            Log::info("Import committed. New: $countNew, Updated/Checked: $countUpdated");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
