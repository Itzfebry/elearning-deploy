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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("quiz_id");
            $table->text("pertanyaan");
            $table->string("opsi_a");
            $table->string("opsi_b");
            $table->string("opsi_c");
            $table->string("opsi_d");
            $table->enum("jawaban_benar", ['a', 'b', 'c', 'd']);
            $table->integer("level")->comment("Level Soal");
            $table->timestamps();

            $table->foreign('quiz_id')
                ->references('id')
                ->on('quizzes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
