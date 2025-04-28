<?php

namespace App\Repositories;

use App\Models\QuizAttempts;
use App\Models\QuizQuestions;
use App\Models\Quizzes;

class QuizRepository
{
    protected $model;
    protected $modelQuizzes;

    public function __construct(QuizQuestions $quizQuestions, Quizzes $quizzes)
    {
        $this->model = $quizQuestions;
        $this->modelQuizzes = $quizzes;
    }

    public function apiGetQuizzes($id)
    {
        $query = $this->modelQuizzes->where('matapelajaran_id', $id)->get();
        return $query;
    }

    public function getData($request)
    {
        $query = $this->model->with('quiz')
            ->whereHas('quiz', function ($q) use ($request) {
                $q->where('matapelajaran_id', $request->matapelajaran_id)
                    ->where('judul', $request->judul);
            })
            ->whereHas('quiz.mataPelajaran', function ($q) use ($request) {
                $q->where('kelas', $request->kelas)
                    ->where('tahun_ajaran', $request->tahun_ajaran);
            })->get();

        return $query;
    }

    public function nextQuestion($attempt_id)
    {
        $attempt = QuizAttempts::findOrFail((int) $attempt_id);

        // Ambil 1 soal random di level sekarang (level_akhir) yang belum dijawab
        $question = QuizQuestions::where('quiz_id', $attempt->quiz_id)
            ->where('level', $attempt->level_akhir) // level siswa sekarang
            ->whereDoesntHave('attemptAnswers', function ($q) use ($attempt_id) {
                $q->where('attempt_id', $attempt_id);
            })
            ->inRandomOrder()
            ->first();

        if (!$question) {
            return response()->json([
                'message' => 'Tidak ada soal lagi di level ini.',
            ], 404);
        }

        return $question;
    }

}