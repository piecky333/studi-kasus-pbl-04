<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('komentar', function (Blueprint $table) {
            $table->bigIncrements('id_komentar');
            $table->unsignedBigInteger('id_berita');
            $table->unsignedBigInteger('id_user');
            $table->text('isi');
            $table->timestamps();

            // 🔗 Relasi ke tabel berita
            $table->foreign('id_berita')
                  ->references('id_berita')
                  ->on('berita')
                  ->onDelete('cascade');

            // 🔗 Relasi ke tabel user 
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('user')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('komentar');
    }
};
