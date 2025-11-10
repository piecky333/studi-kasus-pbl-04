<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('komentar', function (Blueprint $table) {
            $table->bigIncrements('id_komentar');
            $table->unsignedBigInteger('id_berita');
            
            // 1. DIBUAT NULLABLE: Boleh kosong jika komentator adalah tamu
            $table->unsignedBigInteger('id_user')->nullable(); 
            
            // 2.  Wajib diisi jika tamu, diisi otomatis jika login
            $table->string('nama_komentator'); 
            
            $table->text('isi');
            $table->timestamps();

            // Relasi ke berita (Sudah Benar)
            $table->foreign('id_berita')
                  ->references('id_berita')
                  ->on('berita')
                  ->onDelete('cascade');

            // 3. UBAH JADI 'SET NULL': Jika user dihapus, komentarnya tetap ada
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('user')
                  ->onDelete('set null'); // <-- Lebih aman daripada 'cascade'
        });
    }

    public function down(): void {
        Schema::dropIfExists('komentar');
    }
};