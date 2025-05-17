<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempts extends Model
{
    protected $table = "quiz_attempts";

    protected $fillable = [
        "quiz_id",
        "nisn",
        "skor",
        "level_akhir",
        "jumlah_soal_dijawab",
        "benar",
    ];

    public function attemptAnswers()
    {
        return $this->hasMany(QuizAttemptAnswers::class, 'attempt_id');
    }

    public function jumlahJawaban()
    {
        return $this->hasMany(QuizAttemptAnswers::class, 'attempt_id')->count();
    }

    public function quizzes()
    {
        return $this->belongsTo(Quizzes::class, "quiz_id", "id");
    }
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, "nisn", "nisn");
    }
}
