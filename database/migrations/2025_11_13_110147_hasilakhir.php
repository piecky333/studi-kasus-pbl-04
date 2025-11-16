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
        Schema::create('hasil_akhir', function (Blueprint $table) {
            // PK id_hasil
            $table->bigIncrements('id_hasil');
            
            // FK id_alternatif
            $table->unsignedBigInteger('id_alternatif')->unique(); // Unique karena setiap alternatif hanya punya 1 hasil akhir

            // Kolom hasil
            $table->decimal('skor_akhir', 8, 4); // Nilai V
            $table->unsignedSmallInteger('rangking'); // Peringkat

            // Definisi Foreign Key
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
        Schema::dropIfExists('hasil_akhir');
    }
};