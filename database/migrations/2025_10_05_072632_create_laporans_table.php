<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->unsignedBigInteger('id_user'); // Relasi ke tabel user (pelapor)
            $table->unsignedBigInteger('id_terlapor')->nullable(); // Relasi ke tabel terlapor
            $table->string('judul_laporan', 150);
            $table->text('isi_laporan');
            $table->dateTime('tanggal_lapor')->default(now());
            $table->string('bukti_laporan', 255)->nullable(); // File bukti opsional
            $table->enum('status_laporan', ['Menunggu', 'Diproses', 'Selesai'])->default('Menunggu');
            $table->unsignedBigInteger('id_admin')->nullable(); // Admin yang menangani laporan
            $table->timestamps();

            // Relasi ke tabel user
            $table->foreign('id_user')
            ->references('id_user')
            ->on('user')
            ->onDelete('cascade');

            // Relasi ke tabel terlapor (opsional)
            $table->foreign('id_terlapor')
            ->references('id_terlapor')
            ->on('terlapor')
            ->onDelete('set null');

            // Relasi ke tabel admin (opsional)
            $table->foreign('id_admin')
            ->references('id_admin')
            ->on('admin')
            ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};