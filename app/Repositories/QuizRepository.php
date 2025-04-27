<?php

namespace App\Repositories;

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

}