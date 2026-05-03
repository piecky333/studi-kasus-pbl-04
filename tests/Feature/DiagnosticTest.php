<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Kriteria;
use App\Models\HasilAkhir;
use App\Models\Alternatif;
use App\Models\DataMahasiswa;
use App\Models\SubKriteria;
use Illuminate\Support\Facades\DB;

class DiagnosticTest extends TestCase
{
    /**
     * Diagnostic: Check Admin Integrity (formerly check_admin.php)
     */
    public function test_admin_integrity()
    {
        $user = User::where('email', 'admin@politala.ac.id')->first();
        if (!$user) {
            $this->markTestSkipped('Default admin user not found.');
        }

        $admin = Admin::where('id_user', $user->id_user)->first();
        $this->assertNotNull($admin, 'Admin record missing for user admin@politala.ac.id');
    }

    /**
     * Diagnostic: Check SPK Scores (formerly debug_scores.php)
     */
    public function test_spk_scores_exist()
    {
        $results = HasilAkhir::all();
        $this->assertGreaterThan(0, $results->count(), 'SPK results (hasil_akhir) table is empty.');
    }

    /**
     * Diagnostic: Verify Data Relations (formerly verify_data.php)
     */
    public function test_alternatif_mahasiswa_relation()
    {
        $alternatif = Alternatif::first();
        if (!$alternatif) {
            $this->markTestSkipped('No Alternatif found.');
        }

        $mahasiswa = $alternatif->mahasiswa;
        $this->assertNotNull($mahasiswa, 'Mahasiswa relation failed for Alternatif ID ' . $alternatif->id_alternatif);
    }

    /**
     * Diagnostic: SubKriteria Consistency (formerly check_subkriteria.php)
     */
    public function test_subkriteria_count()
    {
        $kriterias = Kriteria::withCount('subKriteria')->get();
        foreach ($kriterias as $k) {
            if ($k->sumber_data === 'kualitatif') {
                $this->assertGreaterThan(0, $k->sub_kriteria_count, "Kriteria {$k->kode_kriteria} is kualitatif but has no sub-kriteria.");
            }
        }
    }
}
