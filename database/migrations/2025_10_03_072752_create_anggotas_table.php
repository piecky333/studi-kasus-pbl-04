<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id('id_anggota');
            $table->unsignedBigInteger('id_user')->nullable();       // Relasi ke USER
            $table->unsignedBigInteger('id_mahasiswa')->nullable();  // Relasi ke DT_MAHASISWA
            $table->unsignedBigInteger('id_divisi')->nullable();     // Relasi ke DIVISI
            $table->string('nama', 100);                             // Nama anggota
            $table->string('jabatan', 100);                          // Jabatan (Ketua, Sekretaris, Bendahara, dsb)
            $table->timestamps();

            // Relasi ke tabel user
            $table->foreign('id_user')
                ->references('id_user')
                ->on('user')
                ->onDelete('set null');

            // Relasi ke tabel mahasiswa
            $table->foreign('id_mahasiswa')
                ->references('id_mahasiswa')
                ->on('dt_mahasiswa')
                ->onDelete('set null');

            // Relasi ke tabel divisi
            $table->foreign('id_divisi')
                ->references('id_divisi')
                ->on('divisi')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};