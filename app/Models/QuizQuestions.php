<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestions extends Model
{
    protected $table = "quiz_questions";

    protected $fillable = [
        "quiz_id",
        "pertanyaan",
        "opsi_a",
        "opsi_b",
        "opsi_c",
        "opsi_d",
        "jawaban_bener",
        "level",
    ];

    public function quiz()
    {
        return $this->belongsTo(Quizzes::class, "quiz_id", "id");
    }
}
