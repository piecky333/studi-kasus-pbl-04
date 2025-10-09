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
    if (!Schema::hasTable('berita')) {
        Schema::create('berita', function (Blueprint $table) {
            $table->id('id_berita');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('cascade');
            $table->string('judul_berita', 200);
            $table->text('isi_berita');
            $table->string('gambar_berita', 255);
           $table->date('tanggal')->default(DB::raw('CURRENT_DATE'));

            $table->timestamps();
        });
    }
}
};