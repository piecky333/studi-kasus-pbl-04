<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->bigIncrements('id_pengaduan');
            $table->unsignedBigInteger('id_user');
            $table->date('tanggal_pengaduan');
            $table->string('jenis_kasus');
            $table->text('deskripsi');
            $table->enum('status_validasi', ['menunggu', 'proses', 'selesai'])->default('menunggu');
            $table->timestamps();

            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('user')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pengaduan');
    }
};
