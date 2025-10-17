<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sanksi', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id_sanksi');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->string('jenis_sanksi');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_sanksi')->nullable();
            $table->timestamps();

            $table->foreign('id_mahasiswa')
                  ->references('id_mahasiswa')
                  ->on('mahasiswa')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('sanksi');
    }
};
