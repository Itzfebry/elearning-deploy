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
        Schema::create('submit_tugas', function (Blueprint $table) {
            $table->id();
            $table->date("tanggal");
            $table->String("nisn", 12);
            $table->unsignedBigInteger("tugas_id");
            $table->text("text")->nullable(true);
            $table->String("file")->nullable(true);
            $table->timestamps();

            $table->foreign('nisn')
                ->references('nisn')
                ->on('siswa')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('tugas_id')
                ->references('id')
                ->on('tugas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submit_tugaas');
    }
};
