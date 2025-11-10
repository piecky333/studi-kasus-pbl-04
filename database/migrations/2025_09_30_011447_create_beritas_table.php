<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('berita', function (Blueprint $table) {
            $table->bigIncrements('id_berita');
            $table->unsignedBigInteger('id_user');
            $table->string('judul_berita');
            $table->text('isi_berita');
            $table->string('kategori')->default('kegiatan')->index();
            $table->string('gambar_berita')->nullable();
            $table->timestamps();

            // foreign key manual
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('user')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('berita');
    }
};
