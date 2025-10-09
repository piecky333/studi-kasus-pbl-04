<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terlapor', function (Blueprint $table) {
            $table->id('id_terlapor');
            $table->string('nama_terlapor', 100);
            $table->string('nim_terlapor', 20)->nullable(); // NIM opsional
            $table->text('keterangan'); // Penjelasan kasus/alasan dilaporkan
            $table->unsignedBigInteger('id_mahasiswa')->nullable(); // Relasi opsional ke DT_MAHASISWA
            $table->timestamps();

            // Relasi ke tabel dt_mahasiswa
            $table->foreign('id_mahasiswa')
            ->references('id_mahasiswa')
            ->on('dt_mahasiswa')
            ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terlapor');
    }
};