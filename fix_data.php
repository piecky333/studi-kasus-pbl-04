<?php

use App\Models\admin\Prestasi;

$prestasis = Prestasi::all();
$count = 0;

foreach ($prestasis as $p) {
    // 1. Fix Status
    $p->status_validasi = 'disetujui';
    
    // 2. Fix Juara (Recover from nama_kegiatan if empty)
    if (empty($p->juara)) {
        // Seeder format: 'Kegiatan ' . $juara
        if (str_starts_with($p->nama_kegiatan, 'Kegiatan ')) {
            $p->juara = str_replace('Kegiatan ', '', $p->nama_kegiatan);
        } else {
             // Fallback default
             $p->juara = 'Juara 3'; 
        }
    }
    
    $p->save();
    $count++;
}

echo "Fixed $count prestasi records (Status set to 'disetujui' and Juara populated).\n";
