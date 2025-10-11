<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('prestasi', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id_prestasi');
            $table->unsignedBigInteger('id_dtmahasiswa'); // FK ke dt_mahasiswa
            $table->string('nama_prestasi');
            $table->string('tingkat'); // contoh: lokal, nasional, internasional
            $table->string('peringkat')->nullable();
            $table->year('tahun');
            $table->timestamps();

            // ✅ Relasi foreign key yang benar
            $table->foreign('id_dtmahasiswa')
                  ->references('id_dtmahasiswa')
                  ->on('dt_mahasiswa')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('prestasi');
    }
};
