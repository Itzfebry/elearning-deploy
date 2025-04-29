<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\QuizAttemptAnswers;
use App\Models\QuizAttempts;
use App\Models\QuizQuestions;
use App\Repositories\QuizRepository;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    use ApiResponse;
    protected $param;
    public function __construct(QuizRepository $quizzes)
    {
        $this->param = $quizzes;
    }
    public function index(Request $request)
    {
        $data = $this->param->apiGetQuizzes($request->matapelajaran_id);
        return $this->okApiResponse($data);
    }

    public function start(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'nisn' => 'required|string',
        ]);

        $attempt = QuizAttempts::create([
            'quiz_id' => $request->quiz_id,
            'nisn' => $request->nisn,
            'skor' => 0,
            'level_akhir' => 1,
        ]);

        return response()->json([
            'attempt_id' => $attempt->id,
            'message' => 'Quiz dimulai.',
        ]);
    }


    public function nextQuestion($attempt_id)
    {
        try {
            $data = $this->param->nextQuestion($attempt_id);
            return $this->okApiResponse($data);
        } catch (\Exception $e) {
            return $this->errorApiResponse('error', $e->getMessage());
        }
    }

    public function answer(Request $request, $attempt_id)
    {
        try {
            $data = $this->param->answer($request, $attempt_id);
            return $this->okApiResponse($data);
        } catch (\Exception $e) {
            return $this->errorApiResponse('error', $e->getMessage());
        }
    }

    public function getFinishQuiz(Request $request)
    {
        try {
            $data = $this->param->getFinishQuiz($request->quiz_id);
            return $this->okApiResponse($data);
        } catch (\Exception $e) {
            return $this->errorApiResponse('error', $e->getMessage());
        }
    }

}
