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
        Schema::table('kriteria', function (Blueprint $table) {
            if (!Schema::hasColumn('kriteria', 'sumber_data')) {
                $table->enum('sumber_data', ['Manual', 'Prestasi', 'Sanksi', 'Pengaduan', 'Berita'])->default('Manual');
            }
            if (!Schema::hasColumn('kriteria', 'atribut_sumber')) {
                $table->string('atribut_sumber')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kriteria', function (Blueprint $table) {
            if (Schema::hasColumn('kriteria', 'sumber_data')) {
                $table->dropColumn('sumber_data');
            }
            if (Schema::hasColumn('kriteria', 'atribut_sumber')) {
                $table->dropColumn('atribut_sumber');
            }
        });
    }
};
