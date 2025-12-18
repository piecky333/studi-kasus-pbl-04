<?php

use App\Models\admin\Datamahasiswa;
use App\Models\spkkeputusan;
use App\Models\alternatif;
use App\Models\penilaian;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$keputusan = spkkeputusan::first();
echo "Keputusan: " . $keputusan->nama_keputusan . "\n";

$alternatifs = alternatif::where('id_keputusan', $keputusan->id_keputusan)->get();

foreach ($alternatifs as $alt) {
    // Find Mahasiswa name
    $mhs = Datamahasiswa::find($alt->id_mahasiswa);
    $name = $mhs ? $mhs->nama : "Unknown ($alt->id_mahasiswa)";
    
    echo str_pad($name, 25) . " | ";

    // Get Nilai for C1, C2, C3, C4
    $penilaians = penilaian::where('id_alternatif', $alt->id_alternatif)->get();
    
    // Sort by id_kriteria to match C1, C2, C3, C4 approximation (or map manually if we have codes)
    $mapped = [];
    foreach($penilaians as $p) {
        $mapped[$p->kriteria->kode_kriteria ?? $p->id_kriteria] = $p->nilai;
    }
    
    // Assume C1=IPK, C2=Tingkat, C3=Juara, C4=Jumlah
    // But codes might be different. Let's print what we have
    // Try to sort by Key
    ksort($mapped);
    foreach($mapped as $code => $val) {
        echo "$code:$val | ";
    }
    echo "\n";
}
