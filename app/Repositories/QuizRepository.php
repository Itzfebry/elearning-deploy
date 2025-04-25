<?php

namespace App\Repositories;

use App\Models\QuizQuestions;

class QuizRepository
{
    protected $model;

    public function __construct(QuizQuestions $quizQuestions)
    {
        $this->model = $quizQuestions;
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