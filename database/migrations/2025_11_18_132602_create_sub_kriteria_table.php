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

            // HARUS merujuk ke 'id_keputusan' di tabel 'keputusan'
            $table->unsignedBigInteger('id_keputusan');
            $table->foreign('id_keputusan')->references('id_keputusan')->on('spkkeputusan')->onDelete('cascade');

            // HARUS merujuk ke 'id_kriteria' di tabel 'kriteria'
            $table->unsignedBigInteger('id_kriteria');
            $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria')->onDelete('cascade');

            $table->string('nama_subkriteria', 100);
            $table->float('nilai')->default(1);

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