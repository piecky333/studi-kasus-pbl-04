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
        Schema::table('tanggapan', function (Blueprint $table) {
            // Add user_id column
            $table->unsignedBigInteger('id_user')->nullable()->after('id_admin');
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');

            // Make id_admin nullable
            $table->unsignedBigInteger('id_admin')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tanggapan', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
            
            // Revert id_admin to not null (caution: this might fail if there are nulls)
            $table->unsignedBigInteger('id_admin')->nullable(false)->change();
        });
    }
};
