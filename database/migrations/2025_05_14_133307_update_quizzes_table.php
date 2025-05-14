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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->integer('jumlah_soal_per_level')->default(5)->after('total_soal_tampil')
                ->comment('Jumlah soal yang akan ditampilkan per level');

            $table->integer('fase')->default(3)->after('jumlah_soal_per_level')
                ->comment('Jumlah fase dalam kuis');

            $table->integer('batas_benar_naik_level')->default(3)->after('fase')
                ->comment('Jumlah jawaban benar berturut-turut untuk naik level');

            $table->enum('level_awal', ['mudah', 'sedang', 'sulit'])->default('mudah')->after('batas_benar_naik_level')
                ->comment('Level awal kuis');

            $table->integer('kkm')->default(75)->after('level_awal')
                ->comment('KKM sebagai acuan kelulusan');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('jumlah_soal_per_level', 'fase', 'batas_benar_naik_level', 'level_awal', 'kkm');
        });
    }
};
