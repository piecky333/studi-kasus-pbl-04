<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('komentar', function (Blueprint $table) {
            //// Tambahkan kolom 'parent_id'
            // Harus nullable() karena komentar induk tidak punya parent
            $table->unsignedBigInteger('parent_id')->nullable()->after('id_berita');

            // Jadikan sebagai foreign key ke tabel 'komentar' itu sendiri
            $table->foreign('parent_id')
                ->references('id_komentar')
                ->on('komentar')
                ->onDelete('cascade'); // Jika komentar induk dihapus, balasannya ikut terhapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komentar', function (Blueprint $table) {
            //
        });
    }
};
