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
        Schema::create('berita', function (Blueprint $table) {
        $table->id('id_berita'); // Primary key, auto increment
        $table->unsignedBigInteger('id_user'); // Relasi ke users
        $table->string('judul_berita', 200); // Judul berita
        $table->text('isi_berita'); // Isi berita
        $table->string('gambar_berita', 255); // Lokasi file gambar
        $table->date('tanggal'); // Tanggal berita dibuat
        $table->timestamps(); // created_at & updated_at otomatis

        // Foreign key ke tabel users (kalau pakai user bawaan Laravel)
        $table->foreign('id_user')
        ->references('id_user')
        ->on('user')
        ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('berita');
}
};