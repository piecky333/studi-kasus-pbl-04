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
            if (Schema::hasColumn('berita', 'id_verifikator')) {
                $table->dropColumn('id_verifikator');
            }
        });

        Schema::table('berita', function (Blueprint $table) {
            $table->unsignedBigInteger('id_verifikator')->nullable()->after('status');
            $table->foreign('id_verifikator')->references('id_user')->on('user')->onDelete('set null');
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
            $table->dropForeign(['id_verifikator']);
            $table->dropColumn('id_verifikator');
        });
    }
};
