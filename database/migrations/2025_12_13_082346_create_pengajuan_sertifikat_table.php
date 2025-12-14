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
        Schema::create('pengajuan_sertifikat', function (Blueprint $table) {
            $table->id('id_pengajuan');
            $table->unsignedBigInteger('id_user');
            $table->string('nama_kegiatan');
            $table->string('jenis_kegiatan'); // e.g., Akademik, Non-Akademik
            $table->date('tanggal_kegiatan');
            $table->string('file_sertifikat'); // Path to file
            $table->text('deskripsi')->nullable();
            $table->string('status')->default('pending'); // pending, verified, rejected
            $table->text('keterangan_admin')->nullable();
            $table->timestamps();

            // Foreign key to users table or mahasiswa table depending on requirement.
            // Assuming linked to users table as per Auth::id() usage.
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_sertifikat');
    }
};
