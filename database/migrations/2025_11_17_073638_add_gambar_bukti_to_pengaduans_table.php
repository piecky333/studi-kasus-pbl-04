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
        // =================================================================
        // UBAH INI: dari 'pengaduans' menjadi 'pengaduan' (tanpa 's')
        // Sesuaikan 'pengaduan' dengan nama tabel yang Anda buat di file migrasi ...create_pengaduans_table.php
        // =================================================================
        Schema::table('pengaduan', function (Blueprint $table) {
            // Ini menambahkan kolom baru 'gambar_bukti_path' setelah kolom 'deskripsi'
            $table->string('gambar_bukti_path')->nullable()->after('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // =================================================================
        // UBAH INI: dari 'pengaduans' menjadi 'pengaduan' (tanpa 's')
        // =================================================================
        Schema::table('pengaduan', function (Blueprint $table) {
            // Ini adalah kebalikan dari 'up', untuk menghapus kolom jika migrasi di-rollback
            $table->dropColumn('gambar_bukti_path');
        });
    }
};