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
        // PENTING: Gunakan nama tabel yang SAMA PERSIS dengan yang di Model (spkkeputusan)
        Schema::create('spkkeputusan', function (Blueprint $table) {
            $table->id('id_keputusan'); // Ini adalah primary key utama
            $table->string('nama_keputusan');
            $table->string('metode_yang_digunakan')->default('SAW')->comment('Contoh: AHP, SAW, AHP-SAW');
            $table->string('status')->default('Draft')->comment('Contoh: Draft, Selesai, Aktif');
            $table->timestamp('tanggal_dibuat')->useCurrent();

            //waktu created_at dan updated_at otomatis ditangani oleh Eloquent jika $timestamps = true
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spkkeputusan');
    }
};