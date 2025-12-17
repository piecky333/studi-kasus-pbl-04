<?php

use App\Models\alternatif;
use App\Models\admin\Datamahasiswa;

// 1. Check Alternatif
$alternatif = alternatif::first();
if (!$alternatif) {
    echo "No Alternatif found.\n";
    exit;
}
echo "Alternatif ID: " . $alternatif->id_alternatif . " - Name: " . $alternatif->nama_alternatif . "\n";

// 2. Check Mahasiswa (using relation)
$mahasiswa = $alternatif->mahasiswa;
if (!$mahasiswa) {
    // Try manual find
    echo "Mahasiswa relation failed. Trying find by ID " . $alternatif->id_mahasiswa . "\n";
    $mahasiswa = Datamahasiswa::find($alternatif->id_mahasiswa);
}

if ($mahasiswa) {
    echo "Mahasiswa Found. ID: " . $mahasiswa->id_mahasiswa . "\n";
    
    // 3. Check Prestasi
    $prestasiCount = $mahasiswa->prestasi()->count();
    $prestasiApproved = $mahasiswa->prestasi()->where('status_validasi', 'disetujui')->count();
    $prestasiAll = $mahasiswa->prestasi()->get();

    echo "Total Prestasi: " . $prestasiCount . "\n";
    echo "Approved Prestasi: " . $prestasiApproved . "\n";
    
    foreach ($prestasiAll as $p) {
        echo " - P ID: " . $p->id_prestasi . " Status: '" . $p->status_validasi . "' (len: " . strlen($p->status_validasi) . ") Tingkat: " . $p->tingkat_prestasi . " Juara: " . $p->juara . "\n";
    }

} else {
    echo "Mahasiswa not found.\n";
}
