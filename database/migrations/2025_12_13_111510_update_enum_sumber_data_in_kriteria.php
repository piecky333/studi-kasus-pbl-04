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
        // Use raw statement because Schema builder has limitations with ENUM modification
        DB::statement("ALTER TABLE kriteria MODIFY COLUMN sumber_data ENUM('Manual', 'Prestasi', 'Sanksi', 'Pengaduan', 'Berita', 'Mahasiswa') DEFAULT 'Manual'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE kriteria MODIFY COLUMN sumber_data ENUM('Manual', 'Prestasi', 'Sanksi', 'Pengaduan', 'Berita') DEFAULT 'Manual'");
    }
};
