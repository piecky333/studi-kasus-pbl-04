<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alternatif', function (Blueprint $table) {
            
            $table->id('id_alternatif'); 

            // KOREKSI: Deklarasi Foreign Key secara eksplisit
            $table->unsignedBigInteger('id_keputusan');
            
            // PENTING: Mereferensikan ke tabel induk 'spkkeputusan' dan kolom 'id'
            $table->foreign('id_keputusan')
                  ->references('id_keputusan') 
                  ->on('spkkeputusan') 
                  ->onDelete('cascade');
            
            // Kolom Data Alternatif (Mahasiswa)
            $table->unsignedBigInteger('id_mahasiswa')->nullable();
            $table->string('nama_alternatif', 255);
            $table->text('keterangan')->nullable();
                  
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternatif');
    }
};