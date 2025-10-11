<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengurus', function (Blueprint $table) {
            $table->bigIncrements('id_pengurus');
            $table->unsignedBigInteger('id_divisi');
            $table->unsignedBigInteger('id_user');
            $table->string('posisi_jabatan');
            $table->timestamps();

            // relasi ke divisi dan user
            $table->foreign('id_divisi')
                  ->references('id_divisi')
                  ->on('divisi')
                  ->onDelete('cascade');

            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('user')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pengurus');
    }
};
