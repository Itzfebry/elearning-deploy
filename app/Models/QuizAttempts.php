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
    ];

    public function attemptAnswers()
    {
        return $this->hasMany(QuizAttemptAnswers::class, 'attempt_id');
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
