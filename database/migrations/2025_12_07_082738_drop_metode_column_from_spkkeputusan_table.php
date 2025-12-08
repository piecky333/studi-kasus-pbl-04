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
        Schema::table('spkkeputusan', function (Blueprint $table) {
            $table->dropColumn('metode_yang_digunakan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spkkeputusan', function (Blueprint $table) {
            $table->string('metode_yang_digunakan', 50)->after('nama_keputusan');
        });
    }
};
