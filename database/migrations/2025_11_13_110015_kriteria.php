<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // KOREKSI: Mengganti Schema::table menjadi Schema::create
        Schema::create('kriteria', function (Blueprint $table) {
            
            $table->id('id_kriteria'); // Primary Key Kriteria
            
            // KOREKSI UTAMA: Deklarasi Foreign Key secara eksplisit
            $table->unsignedBigInteger('id_keputusan');
            
            // PENTING: Mereferensikan ke kolom PRIMARY KEY yang BENAR di tabel induk
            $table->foreign('id_keputusan')
                  ->references('id_keputusan') 
                  ->on('spkkeputusan') 
                  ->onDelete('cascade');
                  
            // Kolom Data Kriteria (sesuai Model Anda)
            $table->string('nama_kriteria');
            $table->string('kode_kriteria', 10)->unique();
            $table->string('jenis_kriteria', 10)->comment('Benefit atau Cost');
            
            $table->decimal('bobot_kriteria', 8, 4)->default(0); 
            
            $table->string('cara_penilaian')->default('Input Langsung');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};