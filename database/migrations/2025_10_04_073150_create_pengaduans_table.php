<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id('id_pengaduan'); 
            
            // Relasi ke tabel user
            $table->foreignId('id_user')->constrained('user', 'id_user')->onDelete('cascade');

            // Konten Pengaduan
            $table->string('judul'); 
            $table->string('jenis_kasus');
            $table->text('deskripsi');
            $table->enum('status', ['Terkirim', 'Diproses', 'Selesai','Ditolak'])->default('Terkirim');
            
            $table->timestamps(); // created_at & updated_at
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('pengaduan');
    }
};
