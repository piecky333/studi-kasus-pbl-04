<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

$kriterias = \App\Models\kriteria::where('id_keputusan', 1)->get();

echo "Total Kriteria Found: " . $kriterias->count() . "\n";
foreach ($kriterias as $k) {
    echo "ID: " . $k->id_kriteria . " | Kode: " . $k->kode_kriteria . " | Nama: " . $k->nama_kriteria . " | Sumber: " . $k->sumber_data . "\n";
}
