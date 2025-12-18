<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengajuan_sertifikat', function (Blueprint $table) {
            $table->string('tingkat_kegiatan')->after('jenis_kegiatan')->nullable(); // Lokal, Provinsi, Nasional, Internasional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_sertifikat', function (Blueprint $table) {
            $table->dropColumn('tingkat_kegiatan');
        });
    }
};
