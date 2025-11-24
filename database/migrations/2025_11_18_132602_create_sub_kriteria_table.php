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
        Schema::create('subkriteria', function (Blueprint $table) {
            $table->id('id_subkriteria');
            
            // Foreign Key ke Kriteria
            $table->unsignedBigInteger('id_kriteria');
            $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria')->onDelete('cascade');

            // Foreign Key ke Keputusan (Optional but good for integrity)
            $table->unsignedBigInteger('id_keputusan');
            $table->foreign('id_keputusan')->references('id_keputusan')->on('spkkeputusan')->onDelete('cascade');

            $table->string('nama_subkriteria');
            $table->decimal('nilai', 8, 4); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subkriteria');
    }
};