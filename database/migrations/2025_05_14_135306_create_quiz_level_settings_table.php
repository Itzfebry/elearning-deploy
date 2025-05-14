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
        Schema::create('quiz_level_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->integer('jumlah_soal_per_level')->default(7);
            $table->integer('level_awal')->default(1);
            $table->integer('batas_naik_level_fase1')->default(4);
            $table->integer('batas_naik_level_fase2')->default(5);
            $table->integer('kkm')->default(70);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_level_settings');
    }
};
