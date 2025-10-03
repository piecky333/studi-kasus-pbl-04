<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::create('jabatans', function (Blueprint $table) {
        $table->id();
        $table->string('nama'); // Field untuk Nama Pegawai
        $table->string('jabatan'); // Field untuk Nama Jabatan (dari dropdown)
        $table->text('deskripsi')->nullable(); // Field untuk Deskripsi, boleh kosong
        $table->timestamps();
        });
    }

// ... bagian bawah sama ...


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatans');
    }
};
