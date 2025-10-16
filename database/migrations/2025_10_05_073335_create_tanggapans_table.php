<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tanggapan', function (Blueprint $table) {
            $table->bigIncrements('id_tanggapan');
            $table->unsignedBigInteger('id_pengaduan');
            $table->unsignedBigInteger('id_admin');
            $table->text('isi_tanggapan');
            $table->date('tanggal_tanggapan');
            $table->timestamps();

            $table->foreign('id_pengaduan')->references('id_pengaduan')->on('pengaduan')->onDelete('cascade');
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('tanggapan');
    }
};
