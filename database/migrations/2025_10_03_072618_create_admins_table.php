<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel admin.
     */
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id('id_admin');
            $table->string('nama', 100);
            $table->string('username', 50)->unique(); // Username harus unik
            $table->string('email', 100)->unique(); // Email harus unik
            $table->string('password', 255); // Password terenkripsi (bcrypt)
            $table->enum('role', ['admin', 'superadmin'])->default('admin'); // Level akses
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif'); // Status akun
            $table->timestamps();
        });
    }

    /**
     * Undo migrasi (hapus tabel admin).
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};