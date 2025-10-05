<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel komentar.
     */
    public function up(): void
    {
        Schema::create('komentar', function (Blueprint $table) {
            $table->id('id_komentar');
            $table->unsignedBigInteger('id_berita'); // Relasi ke tabel BERITA
            $table->unsignedBigInteger('id_user'); // Relasi ke tabel USER
            $table->text('isi'); // Isi komentar
            $table->dateTime('tanggal'); // Tanggal dan waktu komentar dibuat
            $table->timestamps();

            // Relasi ke tabel berita
            $table->foreign('id_berita')
                ->references('id_berita')
                ->on('berita')
                ->onDelete('cascade');

            // Relasi ke tabel user
            $table->foreign('id_user')
                ->references('id_user')
                ->on('user')
                ->onDelete('cascade');
        });
    }

    /**
     * Undo migrasi (hapus tabel komentar).
     */
    public function down(): void
    {
        Schema::dropIfExists('komentar');
    }
};