<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('berita', function (Blueprint $table) {
            if (Schema::hasColumn('berita', 'id_penolak')) {
                $table->dropColumn('id_penolak');
            }
        });

        Schema::table('berita', function (Blueprint $table) {
            $table->unsignedBigInteger('id_penolak')->nullable()->after('id_verifikator');
            $table->foreign('id_penolak')->references('id_user')->on('user')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('berita', function (Blueprint $table) {
            $table->dropForeign(['id_penolak']);
            $table->dropColumn('id_penolak');
        });
    }
};
