<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tanggapan', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id_tanggapan');
            $table->unsignedBigInteger('id_laporan'); // FK ke laporan
            $table->unsignedBigInteger('id_user');    // FK ke user (yang memberi tanggapan)
            $table->text('isi_tanggapan');
            $table->timestamps();

            // ✅ Relasi ke tabel laporan
            $table->foreign('id_laporan')
                  ->references('id_laporan')
                  ->on('laporan')
                  ->onDelete('cascade');

            // ✅ Relasi ke tabel user
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('user')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('tanggapan');
    }
};
