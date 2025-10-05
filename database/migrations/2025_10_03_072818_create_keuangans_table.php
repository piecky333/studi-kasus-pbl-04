<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel keuangan.
     */
    public function up(): void
    {
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id('id_iuran');
            $table->unsignedBigInteger('id_anggota'); // Relasi ke tabel anggota
            $table->unsignedBigInteger('id_divisi'); // Relasi ke tabel divisi
            $table->decimal('jumlah_iuran', 12, 2); // Jumlah uang dibayar
            $table->date('tanggal_bayar'); // Tanggal pembayaran
            $table->date('deadline'); // Batas waktu pembayaran
            $table->string('metode_pembayaran', 50); // Misal: Cash, Transfer, e-Wallet
            $table->timestamps();

            // Relasi ke tabel anggota
            $table->foreign('id_anggota')
            ->references('id_anggota')
            ->on('anggota')->onDelete('cascade');

            // Relasi ke tabel divisi
            $table->foreign('id_divisi')
            ->references('id_divisi')
            ->on('divisi')
            ->onDelete('cascade');
        });
    }

    /**
     * Undo migrasi (hapus tabel keuangan).
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};