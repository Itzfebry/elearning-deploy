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
        Schema::table('quiz_level_settings', function (Blueprint $table) {
            $table->json('skor_level')->after('batas_naik_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_level_settings', function (Blueprint $table) {
            $table->dropColumn('skor_level');
        });
    }
};
