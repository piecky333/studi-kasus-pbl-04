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
            // Membaca baris pertama untuk menentukan indeks kolom secara dinamis.
            // Ini memungkinkan pengguna menempatkan kolom dalam urutan APAPUN.
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
                if (!$nim || !$nama || !$email) {
                    Log::warning("Row " . ($index + 2) . " skipped: Incomplete data (NIM/Nama/Email missing).");
                    continue;
                }

                // =========================================================================
                // 3. PEMBUATAN/PEMBARUAN USER & MAHASISWA
                // =========================================================================
                
                // A. Cari atau Buat Akun User
                $user = User::where('email', $email)->orWhere('username', $nim)->first();
                $isNewUser = false;
                
                if (!$user) {
                     $user = User::create([
                        'nama' => $nama,
                        'username' => $nim,
                        'email' => $email,
                        'password' => Hash::make($nim),
                        'role' => 'mahasiswa',
                    ]);
                    $isNewUser = true;
                    $countNew++;
                    Log::info("User created: " . $user->username);
                } else {
                    $countUpdated++;
                    Log::info("User exists: " . $user->username);
                }

                // B. Cari atau Buat Profil Datamahasiswa
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
                // 4. LOGIKA IMPORT PRESTASI
                // =========================================================================
                // Logika: Jika Nama Prestasi ada ATAU Tingkatan ada, coba import.
                // Jika Nama hilang tapi Tingkatan valid, buat nama otomatis.
                if ($prestasiNama || $prestasiTingkatRaw) {
                     // Peta: Variasi Teks -> Nilai Enum Database
                     $validTingkatMap = [
                         'internasional' => 'Internasional',
                         'nasional' => 'Nasional',
                         'provinsi' => 'Provinsi',
                         'kabupaten/kota' => 'Kabupaten/Kota',
                         'kota' => 'Kabupaten/Kota',
                         'kabupaten' => 'Kabupaten/Kota',
                         'universitas' => 'Internal',
                         'kampus' => 'Internal',
                         'fakultas' => 'Internal', 
                     ];
                     
                     $normalizedTingkat = strtolower(trim($prestasiTingkatRaw));
                     $finalTingkat = $validTingkatMap[$normalizedTingkat] ?? null;

                     // Tangani Nama Prestasi yang Kosong
                     $finalNama = $prestasiNama;
                     if (!$finalNama && $finalTingkat) {
                         $finalNama = "Prestasi Tingkat " . $finalTingkat;
                     }

                     if ($finalTingkat && $finalNama) {
                        prestasi::create([
                            'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                            'id_admin' => $this->idAdmin,
                            'nama_kegiatan' => $finalNama,
                            'jenis_prestasi' => 'Akademik',
                            'tingkat_prestasi' => $finalTingkat,
                            'tahun' => $prestasiTahun ?? date('Y'),
                            'status_validasi' => strtolower($prestasiStatus) == 'disetujui' ? 'disetujui' : 'menunggu', 
                            'deskripsi' => 'Dikirim dari Excel',
                            'bukti_path' => null, 
                        ]);
                        Log::info("Prestasi added for Mahasiswa ID: " . $mahasiswa->id_mahasiswa);
                     } else {
                        // Peringatkan hanya jika user mencoba mengisi tingkatan tapi tidak valid
                        if ($prestasiTingkatRaw) {
                             Log::warning("Skipped Prestasi for Row " . ($index + 2) . ": Invalid Tingkat Prestasi '$prestasiTingkatRaw'. Allowed: " . implode(', ', array_unique($validTingkatMap)));
                        }
                     }
                }

                // =========================================================================
                // 5. LOGIKA IMPORT SANKSI
                // =========================================================================
                if ($sanksiTanggal || $sanksiJenis) {
                    sanksi::create([
                        'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                        'tanggal_sanksi' => $sanksiTanggal ?? date('Y-m-d'),
                        'jenis_sanksi' => $sanksiJenis ?? 'Ringan',
                        'jenis_hukuman' => $sanksiHukuman,
                        'keterangan' => $sanksiKeterangan ?? 'Imported via Excel',
                    ]);
                     Log::info("Sanksi added for Mahasiswa ID: " . $mahasiswa->id_mahasiswa);
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
