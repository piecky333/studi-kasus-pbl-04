<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('keuangan', function (Blueprint $table) {
            $table->bigIncrements('id_keuangan');
            $table->unsignedBigInteger('id_pengurus');
            $table->unsignedBigInteger('id_divisi');
            $table->decimal('jumlah_iuran', 12, 2);
            $table->date('tanggal_bayar');
            $table->date('deadline')->nullable();
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'qris'])->default('cash');
            $table->enum('status_pembayaran', ['belum', 'proses', 'lunas'])->default('belum');
            $table->timestamps();

            $table->foreign('id_pengurus')->references('id_pengurus')->on('pengurus')->onDelete('cascade');
            $table->foreign('id_divisi')->references('id_divisi')->on('divisi')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};
