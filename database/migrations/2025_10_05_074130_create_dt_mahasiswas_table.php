<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel dt_mahasiswa.
     */
    public function up(): void
    {
        Schema::create('dt_mahasiswa', function (Blueprint $table) {
            $table->id('id_mahasiswa');
            $table->unsignedBigInteger('id_admin')->nullable(); // Relasi opsional ke ADMIN
            $table->string('nama', 100);
            $table->string('nim', 20)->unique(); // NIM dibuat unik
            $table->string('semester', 10);
            $table->text('alamat');
            $table->string('no_hp', 15);
            $table->timestamps();

            // Relasi ke tabel admin (yang input data)
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('set null');
        });
    }

    /**
     * Undo migrasi (hapus tabel dt_mahasiswa).
     */
    public function down(): void
    {
        Schema::dropIfExists('dt_mahasiswa');
    }
};