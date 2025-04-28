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
        $request->validate([
            'question_id' => 'required|exists:quiz_questions,id',
            'jawaban_siswa' => 'required|in:a,b,c,d',
        ]);

        $attempt = QuizAttempts::findOrFail($attempt_id);
        $question = QuizQuestions::findOrFail($request->question_id);

        $isCorrect = $request->jawaban_siswa === $question->jawaban_benar ? 1 : 0;

        QuizAttemptAnswers::create([
            'attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'jawaban_siswa' => $request->jawaban_siswa,
            'benar' => $isCorrect,
        ]);

        // Ambil jawaban terakhir (mulai dari jawaban terbaru)
        $answers = QuizAttemptAnswers::where('attempt_id', $attempt->id)
            ->latest()
            ->get();

        // Hitung streak benar berturut-turut
        $streak = 0;
        foreach ($answers as $answer) {
            if ($answer->benar == 1) {
                $streak++;
            } else {
                break; // Stop ketika menemukan jawaban salah
            }
        }

        // Update Level Berdasarkan Hasil Jawaban
        if ($isCorrect) {
            // Kalau benar, cek streak
            if ($streak >= 3 && $attempt->level_akhir < 3) {
                $attempt->level_akhir += 1; // Naik level
            }
        } else {
            // Kalau salah, langsung turun level
            if ($attempt->level_akhir == 3) {
                $attempt->level_akhir = 2;
            } elseif ($attempt->level_akhir == 2) {
                $attempt->level_akhir = 1;
            }
            // Kalau Level 1 salah, tetap di Level 1
        }

        // Simpan perubahan attempt
        $attempt->save();

        // Response ke client
        return response()->json([
            'correct' => $isCorrect,
            'new_level' => $attempt->level_akhir,
        ]);
    }

}
