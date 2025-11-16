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
        Schema::create('alternatif', function (Blueprint $table) {
            // PK id_alternatif
            $table->bigIncrements('id_alternatif');
            
            // FK id_keputusan (Relasi ke tabel spk_keputusan)
            $table->unsignedBigInteger('id_keputusan');
            
            // FK id_mahasiswa (Asumsi sudah ada tabel Mahasiswa)
            $table->unsignedBigInteger('id_mahasiswa'); 

            // Kolom utama
            $table->string('nama_alternatif', 150);
            $table->text('keterangan')->nullable();

            // Definisi Foreign Key
            $table->foreign('id_keputusan')
                  ->references('id_keputusan')
                  ->on('spk_keputusan')
                  ->onDelete('cascade');
            
            // Catatan: Asumsi FK id_mahasiswa ke tabel 'mahasiswa'
            // $table->foreign('id_mahasiswa')->references('id')->on('mahasiswa')->onDelete('cascade');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('alternatif');
    }
};