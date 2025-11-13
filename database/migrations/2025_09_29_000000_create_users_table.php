<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        // Tabel untuk data user
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id_user');
            $table->string('nama');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('no_telpon')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->enum('role', ['admin', 'pengurus', 'user'])->default('user');
            $table->timestamps(); 
        });

        // Tabel untuk token reset password
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('user');
    }
};