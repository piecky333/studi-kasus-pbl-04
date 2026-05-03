<?php

namespace App\Imports;

use App\Models\User;
use App\Models\DataMahasiswa;
use App\Models\Prestasi;
use App\Models\Sanksi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MahasiswaImport
{
    protected $idAdmin;

    public function __construct($idAdmin = null)
    {
        $this->idAdmin = $idAdmin;
    }

    /**
     * Eksekusi proses import dari file Excel.
     *
     * @param string $filePath Path absolut ke file Excel.
     * @throws \Exception
     * @return void
     */
    public function import($filePath)
    {
        Log::info('Starting import process for file: ' . $filePath);

        DB::beginTransaction();
        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet   = $spreadsheet->getActiveSheet();
            $rows        = $worksheet->toArray();

            Log::info('Total rows found: ' . count($rows));

            $headerRow = array_shift($rows);
            $headerMap = [];
            foreach ($headerRow as $index => $colName) {
                if ($colName) {
                    $headerMap[strtolower(trim($colName))] = $index;
                }
            }
            Log::info('Header Map: ' . json_encode($headerMap));

            $getValue = function ($row, $possibleHeaders) use ($headerMap) {
                foreach ($possibleHeaders as $header) {
                    if (isset($headerMap[$header]) && isset($row[$headerMap[$header]])) {
                        return $row[$headerMap[$header]];
                    }
                }
                return null;
            };

            $countNew     = 0;
            $countUpdated = 0;

            foreach ($rows as $index => $row) {
                Log::info("Processing row " . ($index + 2));

                $nim      = $getValue($row, ['nim', 'nomor induk mahasiswa']);
                $nama     = $getValue($row, ['nama', 'nama mahasiswa', 'nama lengkap']);
                $email    = $getValue($row, ['email', 'e-mail', 'alamat email']);
                $semester = $getValue($row, ['semester']);
                $ipk      = $getValue($row, ['ipk', 'indeks prestasi', 'grade']);

                if ($ipk !== null) {
                    $ipk = floatval($ipk);
                    if ($ipk > 4.00) {
                        $ipk = 4.00;
                    } elseif ($ipk < 0) {
                        $ipk = 0.00;
                    }
                }

                $prestasiNama      = $getValue($row, ['nama kegiatan', 'kegiatan', 'prestasi', 'judul prestasi', 'nama prestasi', 'nama_kegiatan']);
                $prestasiTingkatRaw = $getValue($row, ['tingkat prestasi', 'tingkat', 'level', 'tingkat_prestasi', 'tingkatan']);
                $prestasiTahun     = $getValue($row, ['tahun prestasi', 'tahun']);
                $prestasiStatus    = $getValue($row, ['status validasi', 'status', 'validasi', 'status_validasi']) ?? 'menunggu';

                $sanksiTanggalRaw   = $getValue($row, ['tanggal sanksi', 'tanggal', 'tanggal_sanksi']);
                $sanksiJenis        = $getValue($row, ['jenis sanksi', 'sanksi', 'tipe sanksi', 'jenis_sanksi']);
                $sanksiHukuman      = $getValue($row, ['jenis hukuman', 'hukuman', 'jenis_hukuman']);
                $sanksiKeteranganRaw = $getValue($row, ['keterangan', 'deskripsi', 'catatan']);
                $sanksiKeterangan   = $sanksiKeteranganRaw ?? $sanksiJenis;

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

                if (!$nim || !$nama) {
                    Log::warning("Row " . ($index + 2) . " skipped: Incomplete data (NIM/Nama missing).");
                    continue;
                }

                if ($nama) {
                    $cleanName    = strtolower(trim($nama));
                    $emailPrefix  = str_replace(' ', '.', $cleanName);
                    $emailPrefix  = preg_replace('/[^a-z0-9\.]/', '', $emailPrefix);
                    $generatedEmail = $emailPrefix . '@mhs.politala.ac.id';
                } else {
                    $generatedEmail = $email;
                }

                $email  = $generatedEmail;
                $user   = User::where('email', $email)->orWhere('username', $nim)->first();
                $isNewUser = false;

                if (!$user) {
                    $user = User::create([
                        'nama'     => $nama,
                        'username' => $nim,
                        'email'    => $email,
                        'password' => Hash::make($nim),
                        'role'     => 'mahasiswa',
                    ]);
                    $isNewUser = true;
                    $countNew++;
                    Log::info("User created: " . $user->username);
                } else {
                    $countUpdated++;
                    Log::info("User exists: " . $user->username);
                }

                $mahasiswa = DataMahasiswa::firstOrNew(['id_user' => $user->id_user]);

                $mahasiswa->nim      = $nim;
                $mahasiswa->nama     = $nama;
                $mahasiswa->email    = $email;
                $mahasiswa->id_admin = $this->idAdmin;

                if (!is_null($ipk)) {
                    $mahasiswa->ipk = $ipk;
                }

                if (!is_null($semester)) {
                    $mahasiswa->semester = $semester;
                } elseif (!$mahasiswa->exists) {
                    $mahasiswa->semester = 1;
                }

                $mahasiswa->save();

                if ($mahasiswa->wasRecentlyCreated) {
                    Log::info("Mahasiswa profile created for User ID: " . $user->id_user);
                } else {
                    Log::info("Mahasiswa profile updated for User ID: " . $user->id_user);
                }

                $prestasiDeskripsi = $getValue($row, ['deskripsi prestasi', 'keterangan prestasi', 'deskripsi']);
                $prestasiJenis     = $getValue($row, ['jenis prestasi', 'jenis', 'kategori prestasi']);
                $prestasiJuaraRaw  = $getValue($row, ['juara', 'peringkat', 'rank']);

                $parsedPrestasi = $this->parseJuaraAndKegiatan($prestasiJuaraRaw, $prestasiNama);
                $finalJuara     = $parsedPrestasi['juara'];
                $finalNama      = $parsedPrestasi['kegiatan'];

                if ($prestasiNama || $prestasiTingkatRaw) {
                    $validTingkatMap = [
                        'internasional'   => 'Internasional',
                        'nasional'        => 'Nasional',
                        'provinsi'        => 'Provinsi',
                        'kabupaten/kota'  => 'Kabupaten/Kota',
                        'kota'            => 'Kabupaten/Kota',
                        'kabupaten'       => 'Kabupaten/Kota',
                        'universitas'     => 'Internal',
                        'internal'        => 'Internal',
                        'kampus'          => 'Internal',
                        'fakultas'        => 'Internal',
                    ];

                    $normalizedTingkat = strtolower(trim($prestasiTingkatRaw));
                    $finalTingkat      = $validTingkatMap[$normalizedTingkat] ?? null;
                    $finalNama         = $finalNama ?: "Prestasi Tingkat " . ($finalTingkat ?? 'Lainnya');

                    if ($finalTingkat || $prestasiNama) {
                        Prestasi::updateOrCreate(
                            [
                                'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                                'nama_kegiatan' => $finalNama,
                                'tahun'        => $prestasiTahun ?? date('Y'),
                            ],
                            [
                                'id_admin'        => $this->idAdmin,
                                'jenis_prestasi'  => (str_contains(strtolower(trim($prestasiJenis ?? '')), 'non')) ? 'Non-Akademik' : 'Akademik',
                                'tingkat_prestasi' => $finalTingkat ?? 'Internal',
                                'juara'           => $finalJuara,
                                'status_validasi' => 'disetujui',
                                'deskripsi'       => $prestasiDeskripsi ?? 'Dikirim dari Excel',
                            ]
                        );
                        Log::info("Prestasi updated/created for NIM: " . $nim);
                    }
                }

                if ($sanksiJenis || $sanksiHukuman) {
                    Sanksi::updateOrCreate(
                        [
                            'id_mahasiswa'  => $mahasiswa->id_mahasiswa,
                            'jenis_sanksi'  => $sanksiJenis ?? 'Ringan',
                            'tanggal_sanksi' => $sanksiTanggal ?? date('Y-m-d'),
                        ],
                        [
                            'jenis_hukuman' => $sanksiHukuman ?? 'Teguran Lisan',
                            'keterangan'    => $sanksiKeterangan ?? 'Dikirim dari Excel',
                        ]
                    );
                    Log::info("Sanksi updated/created for NIM: " . $nim);
                }
            }

            DB::commit();
            Log::info("Import committed. New: $countNew, Updated/Checked: $countUpdated");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function parseJuaraAndKegiatan($rawString, $existingKegiatan = '')
    {
        $result = [
            'juara'    => $rawString,
            'kegiatan' => $existingKegiatan,
        ];

        if (empty($rawString)) return $result;

        $pattern = '/^(Juara\s+(\d+|Harapan\s+\d+|Wakil\s+\w+|Favorit|Terbaik)|Medali\s+\w+(\s+\(.*\))?|Finalis|.*Champion.*|.*Place|Lolos\s+Seleksi.*|Top\s+\d+|.*Besar|Peserta\s+Terpilih)\s*(.*)/i';

        if (preg_match($pattern, $rawString, $matches)) {
            $result['juara'] = trim($matches[1]);
            $rest            = isset($matches[4]) ? trim($matches[4]) : '';

            if (!empty($rest)) {
                $rest             = ltrim($rest, "-: ");
                $result['kegiatan'] = empty($existingKegiatan) ? $rest : $existingKegiatan . ' - ' . $rest;
            }
        }

        return $result;
    }
}
