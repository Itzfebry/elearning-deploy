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
        "total_soal_tampil",
        "matapelajaran_id",
    ];

    public function levelSetting()
    {
        return $this->hasOne(QuizLevelSetting::class);
    }

    public function quizAttempt()
    {
        return $this->hasOne(QuizAttempts::class, "quiz_id", "id");
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, "matapelajaran_id", "id");
    }
}
