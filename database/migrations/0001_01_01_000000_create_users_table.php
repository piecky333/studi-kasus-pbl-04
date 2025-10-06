<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id('id_user'); // Primary key
            $table->string('nama', 100); // Nama lengkap user
            $table->string('username', 50)->unique(); // Username unik
            $table->string('email', 100)->unique(); // Email unik
            $table->string('password', 255); // Password (hash bcrypt/MD5)
            $table->enum('role', ['user', 'pelapor'])->default('user'); // Hak akses
            $table->timestamps(); // created_at dan updated_at otomatis
        });

        // Opsional — kalau masih mau fitur reset password dan session Laravel
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->unsignedBigInteger('id_user')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();

            // Relasi ke tabel user
            $table->foreign('id_user')
                ->references('id_user')
                ->on('user')
                ->onDelete('set null');
        });
    }

    /**
     * Undo migrasi (hapus tabel user).
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('user');
    }
};