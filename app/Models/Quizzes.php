<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quizzes extends Model
{
    protected $table = "quizzes";

    protected $fillable = [
        "judul",
        "deskripsi",
        "total_soal",
        "matapelajaran_id",
    ];

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, "matapelajaran_id", "id");
    }
}
