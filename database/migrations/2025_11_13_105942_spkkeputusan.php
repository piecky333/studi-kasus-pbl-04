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
        Schema::create('spk_keputusan', function (Blueprint $table) {
            // PK id_keputusan
            $table->bigIncrements('id_keputusan');
            
            // Kolom utama
            $table->string('nama_keputusan', 150);
            $table->string('metode_yang_digunakan', 50); // SAW, AHP, dll.
            $table->timestamp('tanggal_dibuat')->useCurrent();
            $table->enum('status', ['draft', 'active', 'finished', 'archived'])->default('draft');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_keputusan');
    }
};