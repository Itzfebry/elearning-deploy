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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->comment('Nama Kuis');
            $table->text('deskripsi')->nullable();
            $table->integer('total_soal')->comment('Jumlah soal Maksimal');
            $table->unsignedBigInteger("matapelajaran_id");
            $table->timestamps();

            $table->foreign('matapelajaran_id')
                ->references('id')
                ->on('matapelajaran')
                ->onUpdate('cascade')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
