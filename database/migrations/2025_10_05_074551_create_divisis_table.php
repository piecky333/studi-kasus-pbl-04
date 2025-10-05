<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel divisi.
     */
    public function up(): void
    {
        Schema::create('divisi', function (Blueprint $table) {
            $table->id('id_divisi');
            $table->string('nama_divisi', 100); // Nama divisi (Humas, Keuangan, IT, dll)
            $table->text('isi_divisi'); // Deskripsi divisi
            $table->string('foto_divisi', 255)->nullable(); // Lokasi file foto/logo (opsional)
            $table->timestamps();
        });
    }

    /**
     * Undo migrasi (hapus tabel divisi).
     */
    public function down(): void
    {
        Schema::dropIfExists('divisi');
    }
};