<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('terlapor', function (Blueprint $table) {
            $table->bigIncrements('id_terlapor');
            $table->unsignedBigInteger('id_pengaduan');
            $table->string('nama_terlapor');
            $table->string('no_hp_terlapor')->nullable();
            $table->string('status_terlapor')->default('belum diverifikasi');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_pengaduan')->references('id_pengaduan')->on('pengaduan')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('terlapor');
    }
};
