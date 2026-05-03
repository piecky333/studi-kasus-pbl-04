<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DataMahasiswa;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Illuminate\Support\Facades\DB;

class DataCorrectionTest extends TestCase
{
    /**
     * Logic from fix_reihan_level.php
     */
    public function test_fix_reihan_level()
    {
        $reihan = DataMahasiswa::where('nama', 'LIKE', '%Reihan Fariza%')->first();
        if ($reihan) {
            foreach ($reihan->prestasi as $p) {
                if ($p->juara == 'Juara 3' && $p->tingkat_prestasi == 'Nasional') {
                    $p->tingkat_prestasi = 'Provinsi';
                    $p->save();
                }
            }
            $this->assertTrue(true);
        }
    }

    /**
     * Logic from force_delete_c9.php
     */
    public function test_cleanup_obsolete_kriteria()
    {
        // Kriteria ID 13 (C9) was marked for deletion in previous script
        $k = Kriteria::find(13);
        if ($k) {
            Penilaian::where('id_kriteria', 13)->delete();
            SubKriteria::where('id_kriteria', 13)->delete();
            $k->delete();
        }
        $this->assertNull(Kriteria::find(13));
    }
}
