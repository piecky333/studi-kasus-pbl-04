<?php

use App\Models\admin\Datamahasiswa;
use App\Models\admin\Prestasi;

// 1. M. REZA FAHLEVI
$reza = Datamahasiswa::where('nama', 'LIKE', '%Reza Fahlevi%')->first();
if ($reza) {
    echo "Found Reza. Checking Prestasi...\n";
    $count = $reza->prestasi()->count();
    if ($count < 2) {
        echo "Adding missing Prestasi for Reza...\n";
        Prestasi::create([
            'id_mahasiswa' => $reza->id_mahasiswa,
            'id_admin' => $reza->id_admin, // assume same admin
            'nama_kegiatan' => 'Kegiatan Tambahan Juara 2 Embu Berpasangan Putera',
            'jenis_prestasi' => 'Akademik',
            'tingkat_prestasi' => 'Provinsi',
            'juara' => 'Juara 2',
            'tahun' => date('Y'),
            'status_validasi' => 'disetujui', 
            'deskripsi' => 'Added by Fix Script',
        ]);
        echo "Reza: Added 1 Prestasi.\n";
    } else {
        echo "Reza has $count achievements. No action.\n";
    }
}

// 2. Reihan Fariza
$reihan = Datamahasiswa::where('nama', 'LIKE', '%Reihan Fariza%')->first();
if ($reihan) {
    echo "Found Reihan. Checking Prestasi...\n";
    $count = $reihan->prestasi()->count();
    if ($count < 2) {
        echo "Adding missing Prestasi for Reihan...\n";
        // He needs Juara 3 (Provinsi) if missing, or Juara 1 (Nasional) if missing.
        // Assuming the seeder added one of them.
        // Let's check which one exists.
        $existing = $reihan->prestasi->first();
        if ($existing) {
             echo "Existing: " . $existing->juara . " (" . $existing->tingkat_prestasi . ")\n";
             // If existing is Juara 1 Nasional, add Juara 3 Provinsi
             if (stripos($existing->juara, 'Juara 1') !== false) {
                 $newJuara = 'Juara 3';
                 $newTingkat = 'Provinsi';
             } else {
                 $newJuara = 'Juara 1';
                 $newTingkat = 'Nasional';
             }
        } else {
             // If none, add both (simplified, just add one now, rerull will add other)
             $newJuara = 'Juara 1';
             $newTingkat = 'Nasional';
        }
        
        Prestasi::create([
            'id_mahasiswa' => $reihan->id_mahasiswa,
            'id_admin' => $reihan->id_admin ?? 1,
            'nama_kegiatan' => 'Kegiatan Tambahan ' . $newJuara,
            'jenis_prestasi' => 'Akademik',
            'tingkat_prestasi' => $newTingkat,
            'juara' => $newJuara,
            'tahun' => date('Y'),
            'status_validasi' => 'disetujui',
            'deskripsi' => 'Added by Fix Script',
        ]);
        echo "Reihan: Added 1 Prestasi ($newJuara - $newTingkat).\n";
    } else {
        echo "Reihan has $count achievements. No action.\n";
    }
}
