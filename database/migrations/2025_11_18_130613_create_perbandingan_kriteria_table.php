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
        Schema::create('perbandingan_kriteria', function (Blueprint $table) {
        $table->id('id_perbandingan');
        $table->unsignedBigInteger('id_keputusan');
        $table->unsignedBigInteger('id_kriteria_1');
        $table->unsignedBigInteger('id_kriteria_2');
        $table->float('nilai_perbandingan'); // Nilai skala Saaty 1–9
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbandingan_kriteria');
    }
};
