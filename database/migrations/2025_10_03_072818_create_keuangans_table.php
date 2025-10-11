<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('keuangan', function (Blueprint $table) {
            $table->bigIncrements('id_iuran');

            // 🔧 Kolom foreign key — tipe harus UNSIGNED BIGINT
            $table->unsignedBigInteger('id_divisi');
            $table->unsignedBigInteger('id_pengurus');

            // 🔢 Kolom data lainnya
            $table->double('jumlah_iuran');
            $table->date('tanggal_bayar')->nullable();
            $table->date('deadline')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->enum('status_pembayaran', ['lunas', 'belum_lunas'])->default('belum_lunas');
            $table->timestamps();

            $table->foreign('id_divisi')
                  ->references('id_divisi')
                  ->on('divisi')
                  ->onDelete('cascade');

            $table->foreign('id_pengurus')
                  ->references('id_pengurus')
                  ->on('pengurus')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('keuangan');
    }
};
