<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MahasiswaImport
{
    public function import($filePath)
    {
        Log::info('Starting import process for file: ' . $filePath);

        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            Log::info('Total rows found: ' . count($rows));

            // Remove header row
            $header = array_shift($rows);
            Log::info('Header row removed: ' . json_encode($header));

            foreach ($rows as $index => $row) {
                Log::info("Processing row " . ($index + 2) . ": " . json_encode($row));

                // Asumsi urutan kolom: NIM, Nama, Email, Semester
                // Index: 0 => NIM, 1 => Nama, 2 => Email, 3 => Semester
                
                $nim = $row[0] ?? null;
                $nama = $row[1] ?? null;
                $email = $row[2] ?? null;
                $semester = $row[3] ?? 1;

                if (!$nim || !$nama || !$email) {
                    Log::warning("Row " . ($index + 2) . " skipped: Incomplete data. NIM: $nim, Nama: $nama, Email: $email");
                    continue; // Skip jika data tidak lengkap
                }

                // Cek apakah user dengan email sudah ada
                $existingUser = User::where('email', $email)->first();
                if ($existingUser) {
                    Log::warning("Row " . ($index + 2) . " skipped: Email $email already exists.");
                    continue; // Skip jika sudah ada
                }

                // Buat User baru
                $user = User::create([
                    'nama' => $nama,
                    'username' => $nim, // Gunakan NIM sebagai username
                    'email' => $email,
                    'password' => Hash::make($nim), // Password default adalah NIM
                    'role' => 'user', // Role harus 'user' sesuai enum database
                ]);

                // Buat data Mahasiswa
                Mahasiswa::create([
                    'id_user' => $user->id_user,
                    'nim' => $nim,
                    'nama' => $nama,
                    'email' => $email,
                    'semester' => $semester,
                ]);

                Log::info("Row " . ($index + 2) . " success: Created user and mahasiswa for $nama ($nim)");
            }
        } catch (\Exception $e) {
            Log::error('Import failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
