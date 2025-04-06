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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->date("tanggal");
            $table->date("tenggat");
            $table->text("nama");
            $table->unsignedBigInteger("matapelajaran_id");
            $table->unsignedBigInteger("kelas_id");
            $table->timestamps();

            $table->foreign('matapelajaran_id')
                ->references('id')
                ->on('matapelajaran')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('kelas_id')
                ->references('id')
                ->on('kelas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
