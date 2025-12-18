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
        Schema::table('jabatan', function (Blueprint $table) {
            // Drop foreign key first if it exists. 
            // Constraint name usually 'jabatan_id_divisi_foreign' but good to be safe.
            // We'll try to drop foreign by array syntax which is safer.
            $table->dropForeign(['id_divisi']); 
            $table->dropColumn('id_divisi');
            
            // Add deskripsi column
            $table->text('deskripsi')->nullable()->after('nama_jabatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_divisi')->nullable()->after('id_jabatan');
            $table->foreign('id_divisi')->references('id_divisi')->on('divisi');
            $table->dropColumn('deskripsi');
        });
    }
};
