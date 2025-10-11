<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jabatan', function (Blueprint $table) {
            $table->bigIncrements('id_jabatan');
            $table->unsignedBigInteger('id_divisi'); // tipe harus sama dengan id_divisi di tabel divisi
            $table->string('nama_jabatan');
            $table->timestamps();

            $table->foreign('id_divisi')
                  ->references('id_divisi')
                  ->on('divisi')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('jabatan');
    }
};
