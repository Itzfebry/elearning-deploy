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
            $table->String("kelas", 10);
            $table->string("tahun_ajaran", 9);
            $table->String("wali_nip", 18);
            $table->timestamps();

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

            $table->foreign('tahun_ajaran')
                ->references('tahun')
                ->on('tahun_ajaran')
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
