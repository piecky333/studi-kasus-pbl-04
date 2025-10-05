<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel mahasiswa_berprestasi.
     */
    public function up(): void
    {
        Schema::create('mahasiswa_berprestasi', function (Blueprint $table) {
            $table->id('id_mhsprestasi');
            $table->unsignedBigInteger('id_mahasiswa'); // Relasi ke tabel DT_MAHASISWA
            $table->string('nama', 100); // Nama mahasiswa (redundansi opsional)
            $table->string('nim', 20); // NIM mahasiswa
            $table->year('tahun'); // Tahun prestasi
            $table->enum('tingkat', ['Kampus', 'Kota', 'Provinsi', 'Nasional', 'Internasional']); // Level prestasi
            $table->string('nama_lomba', 200); // Nama lomba atau prestasi
            $table->enum('jenis_prestasi', ['Akademik', 'Non-Akademik']); // Jenis prestasi
            $table->enum('status_validasi', ['Tervalidasi', 'Menunggu', 'Ditolak'])->default('Menunggu'); // Status validasi
            $table->timestamps();

            // Relasi ke tabel dt_mahasiswa
            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('dt_mahasiswa')->onDelete('cascade');
        });
    }

    /**
     * Undo migrasi (hapus tabel mahasiswa_berprestasi).
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_berprestasi');
    }
};