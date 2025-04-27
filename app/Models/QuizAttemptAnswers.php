<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswers extends Model
{
    protected $table = "quiz_attempt_answers";

    protected $fillable = [
        "attempt_id",
        "question_id",
        "jawaban_siswa",
        "benar",
    ];

    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempts::class, "attempt_id", "id");
    }

    public function quizQuestion()
    {
        return $this->belongsTo(QuizQuestions::class, "question_id", "id");
    }
}
