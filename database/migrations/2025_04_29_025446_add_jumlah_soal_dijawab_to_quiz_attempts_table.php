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
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->integer('jumlah_soal_dijawab')->default(0)->after('level_akhir');
            $table->integer('benar_fase1')->default(0)->after('jumlah_soal_dijawab');
            $table->integer('benar_fase2')->default(0)->after('benar_fase1');
            $table->integer('fase')->default(1)->after('benar_fase2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn(['jumlah_soal_dijawab', 'benar_fase1', 'benar_fase2', 'fase']);
        });
    }
};
