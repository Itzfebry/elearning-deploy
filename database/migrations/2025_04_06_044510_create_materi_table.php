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
        Schema::create('materi', function (Blueprint $table) {
            $table->id();
            $table->date("tanggal");
            $table->unsignedBigInteger("matapelajaran_id");
            $table->enum("semester", ["1", "2"]);
            $table->enum("type", ["buku", "video"]);
            $table->string("judul_materi");
            $table->text("deskripsi");
            $table->String("path");
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
        Schema::dropIfExists('materi');
    }
};
