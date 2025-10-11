<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('laporan', function (Blueprint $table) {
            // Gunakan InnoDB agar foreign key bisa aktif
            $table->engine = 'InnoDB';

            $table->bigIncrements('id_laporan');
            
            // Relasi ke tabel user
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('user')
                  ->onDelete('cascade');

            $table->date('tanggal_laporan');
            $table->string('jenis_kasus');
            $table->text('deskripsi');
            $table->enum('status_validasi', ['menunggu', 'proses', 'selesai'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('laporan');
    }
};
