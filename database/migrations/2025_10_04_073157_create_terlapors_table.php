<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('terlapor', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id_terlapor');
            $table->unsignedBigInteger('id_laporan'); // FK ke laporan
            $table->string('nama_terlapor');
            $table->string('nim')->nullable();
            $table->string('jurusan')->nullable();
            $table->timestamps();

            // ✅ Perbaiki FK-nya, refer ke 'id_laporan' (bukan 'id')
            $table->foreign('id_laporan')
                  ->references('id_laporan')
                  ->on('laporan')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('terlapor');
    }
};
