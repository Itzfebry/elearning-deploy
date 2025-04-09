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
        Schema::create('riwayat_kelas', function (Blueprint $table) {
            $table->id();
            $table->String("siswa_nisn", 12);
            $table->String("kelas", 10);
            $table->string("tahun_ajaran", 9);
            $table->String("wali_nip", 18);
            $table->timestamps();

            $table->foreign('siswa_nisn')
                ->references('nisn')
                ->on('siswa')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('kelas')
                ->references('nama')
                ->on('kelas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('wali_nip')
                ->references('nip')
                ->on('guru')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_kelas');
    }
};
