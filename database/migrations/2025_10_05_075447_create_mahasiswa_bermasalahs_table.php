<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel mahasiswa_bermasalah.
     */
    public function up(): void
    {
        Schema::create('mahasiswa_bermasalah', function (Blueprint $table) {
            $table->id('id_mhsbermasalah');
            $table->unsignedBigInteger('id_mahasiswa'); // Relasi ke DT_MAHASISWA
            $table->string('nama', 100); // Nama mahasiswa
            $table->string('jenis_masalah', 100); // Jenis masalah (akademik, disiplin, dll)
            $table->date('tanggal_lapor'); // Tanggal laporan masuk
            $table->enum('status_validasi', ['Menunggu', 'Diproses', 'Selesai'])->default('Menunggu'); // Status laporan
            $table->unsignedBigInteger('id_laporan')->nullable(); // Relasi opsional ke tabel LAPORAN
            $table->timestamps();

            // Relasi ke tabel dt_mahasiswa
            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('dt_mahasiswa')->onDelete('cascade');

            // Relasi opsional ke tabel laporan
            $table->foreign('id_laporan')->references('id_laporan')->on('laporan')->onDelete('set null');
        });
    }

    /**
     * Undo migrasi (hapus tabel mahasiswa_bermasalah).
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_bermasalah');
    }
};