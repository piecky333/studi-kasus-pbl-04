<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            // PK id_penilaian
            $table->bigIncrements('id_penilaian');
            
            // FKs (Relasi ke tabel kriteria dan alternatif)
            $table->unsignedBigInteger('id_kriteria');
            $table->unsignedBigInteger('id_alternatif');

            // Kolom nilai
            $table->decimal('nilai', 8, 4); 
            
            // Memastikan Penilaian unik untuk setiap pasangan Alternatif-Kriteria
            $table->unique(['id_kriteria', 'id_alternatif']);

            // Definisi Foreign Key
            $table->foreign('id_kriteria')
                  ->references('id_kriteria')
                  ->on('kriteria')
                  ->onDelete('cascade');
            
            $table->foreign('id_alternatif')
                  ->references('id_alternatif')
                  ->on('alternatif')
                  ->onDelete('cascade');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};