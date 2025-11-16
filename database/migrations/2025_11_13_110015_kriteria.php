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
        Schema::create('kriteria', function (Blueprint $table) {
            // PK id_kriteria
            $table->bigIncrements('id_kriteria');
            
            // FK id_keputusan (Relasi ke tabel spk_keputusan)
            $table->unsignedBigInteger('id_keputusan');

            // Kolom utama
            $table->string('nama_kriteria', 100);
            $table->string('kode_kriteria', 10)->unique(); // C1, C2, C3, dll.
            $table->enum('jenis_kriteria', ['benefit', 'cost']);
            $table->decimal('bobot_kriteria', 8, 4)->default(0.0000); // Bobot dari AHP/Input

            // Definisi Foreign Key
            $table->foreign('id_keputusan')
                  ->references('id_keputusan')
                  ->on('spk_keputusan')
                  ->onDelete('cascade');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};