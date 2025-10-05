<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel sanksi.
     */
    public function up(): void
    {
        Schema::create('sanksi', function (Blueprint $table) {
            $table->id('id_sanksi');
            $table->unsignedBigInteger('id_mhsbermasalah'); // Relasi ke tabel MAHASISWA_BERMASALAH
            $table->string('jenis_sanksi', 100); // Jenis sanksi (teguran, skors, pembinaan, dll)
            $table->date('tanggal_sanksi'); // Tanggal sanksi dijatuhkan
            $table->enum('status', ['Aktif', 'Selesai'])->default('Aktif'); // Status pelaksanaan sanksi
            $table->unsignedBigInteger('id_admin')->nullable(); // Admin yang memberi sanksi
            $table->timestamps();

            // Relasi ke tabel mahasiswa_bermasalah
            $table->foreign('id_mhsbermasalah')->references('id_mhsbermasalah')->on('mahasiswa_bermasalah')->onDelete('cascade');

            // Relasi ke tabel admin (opsional)
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('set null');
        });
    }

    /**
     * Undo migrasi (hapus tabel sanksi).
     */
    public function down(): void
    {
        Schema::dropIfExists('sanksi');
    }
};