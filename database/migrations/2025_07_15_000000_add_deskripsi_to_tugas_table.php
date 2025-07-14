<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->text('deskripsi')->nullable()->after('nama');
        });
    }
    public function down()
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropColumn('deskripsi');
        });
    }
}; 