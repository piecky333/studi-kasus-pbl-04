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
        Schema::table('kriteria', function (Blueprint $table) {
            // Drop the existing global unique index
            // Note: The index name is usually table_column_unique
            $table->dropUnique('kriteria_kode_kriteria_unique');
            
            // Add a composite unique index scoped to the decision
            $table->unique(['id_keputusan', 'kode_kriteria']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kriteria', function (Blueprint $table) {
            // Revert the changes
            $table->dropUnique(['id_keputusan', 'kode_kriteria']);
            $table->unique('kode_kriteria');
        });
    }
};
