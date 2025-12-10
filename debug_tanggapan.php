<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\laporan\Pengaduan;
use App\Models\laporan\Tanggapan;

$id = 21;
$pengaduan = Pengaduan::find($id);

if (!$pengaduan) {
    echo "Pengaduan with ID $id not found.\n";
    exit;
}

echo "Pengaduan ID: " . $pengaduan->id_pengaduan . "\n";
echo "Judul: " . $pengaduan->judul . "\n";

$tanggapans = Tanggapan::where('id_pengaduan', $id)->get();

echo "Count Tanggapan: " . $tanggapans->count() . "\n";

foreach ($tanggapans as $t) {
    echo "--------------------------------------------------\n";
    echo "ID Tanggapan: " . $t->id_tanggapan . "\n";
    echo "ID Admin: " . ($t->id_admin ?? 'NULL') . "\n";
    echo "ID User: " . ($t->id_user ?? 'NULL') . "\n";
    echo "Isi: " . $t->isi_tanggapan . "\n";
}
