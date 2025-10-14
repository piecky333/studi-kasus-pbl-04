<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('prestasi', function (Blueprint $table) {
            $table->bigIncrements('id_prestasi');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedBigInteger('id_admin')->nullable();
            $table->string('nama_kegiatan');
            $table->string('tingkat_prestasi');
            $table->year('tahun');
            $table->enum('status_validasi', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->timestamps();

            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('prestasi');
    }
};
