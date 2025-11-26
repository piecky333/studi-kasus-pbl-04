<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            if (!Schema::hasColumn('user', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable()->after('no_telpon');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('profile_photo_path');
        });
    }
};
