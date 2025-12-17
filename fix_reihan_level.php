<?php
use App\Models\admin\Datamahasiswa;
$reihan = Datamahasiswa::where('nama', 'LIKE', '%Reihan Fariza%')->first();
if ($reihan) {
    foreach ($reihan->prestasi as $p) {
        if ($p->juara == 'Juara 3' && $p->tingkat_prestasi == 'Nasional') {
            $p->tingkat_prestasi = 'Provinsi'; // Fix to match Excel (Tk 4+3=7)
            $p->save();
            echo "Fixed Reihan Juara 3 to Provinsi.\n";
        }
    }
}
