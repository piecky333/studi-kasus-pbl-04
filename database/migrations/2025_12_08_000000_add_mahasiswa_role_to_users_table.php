<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifikasi kolom role untuk menambahkan 'mahasiswa'
        DB::statement("ALTER TABLE user MODIFY COLUMN role ENUM('admin', 'pengurus', 'user', 'mahasiswa') NOT NULL DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum semula (PERINGATAN: Data 'mahasiswa' mungkin akan bermasalah jika di-rollback)
        DB::statement("ALTER TABLE user MODIFY COLUMN role ENUM('admin', 'pengurus', 'user') NOT NULL DEFAULT 'user'");
    }
};
