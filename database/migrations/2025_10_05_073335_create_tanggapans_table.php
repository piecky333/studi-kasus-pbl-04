<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanggapan', function (Blueprint $table) {
            $table->id('id_tanggapan');
            $table->unsignedBigInteger('id_laporan'); // Relasi ke tabel laporan
            $table->unsignedBigInteger('id_admin'); // Relasi ke tabel admin
            $table->text('tanggapan'); // Isi tanggapan dari admin
            $table->dateTime('tanggal_tanggapan')->default(now()); // Waktu tanggapan diberikan
            $table->timestamps();

            // Relasi ke tabel laporan
            $table->foreign('id_laporan')->references('id_laporan')->on('laporan')->onDelete('cascade');

            // Relasi ke tabel admin
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanggapan');
    }
};