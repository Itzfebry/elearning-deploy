<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizLevelSetting extends Model
{
    protected $table = "quiz_level_settings";

    protected $fillable = [
        'quiz_id',
        'jumlah_soal_per_level',
        'level_awal',
        'batas_naik_level',
        'skor_level',
        'kkm',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quizzes::class);
    }
}
