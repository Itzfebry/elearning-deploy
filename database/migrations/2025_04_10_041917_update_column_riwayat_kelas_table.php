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
        Schema::table('riwayat_kelas', function (Blueprint $table) {
            $table->dropForeign(['siswa_nisn']);
            $table->dropColumn('siswa_nisn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_kelas', function (Blueprint $table) {
            $table->String("siswa_nisn", 12)->after('id');

            $table->foreign('siswa_nisn')
                ->references('nisn')
                ->on('siswa')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
};
