<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('divisi', function (Blueprint $table) {
            $table->bigIncrements('id_divisi');
            $table->string('nama_divisi');
            $table->text('isi_divisi')->nullable();
            $table->string('foto_divisi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('divisi');
    }
};
