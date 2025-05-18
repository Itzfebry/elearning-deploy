<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\QuizAttemptAnswers;
use App\Models\QuizAttempts;
use App\Models\QuizLevelSetting;
use App\Models\QuizQuestions;
use App\Repositories\QuizRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function quizGuru(Request $request)
    {
        $data = $this->param->apiGetQuizzesGuru($request, $request->matapelajaran_id);
        return $this->okApiResponse($data);
    }

    public function start(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'nisn' => 'required|string',
        ]);

        $quizLevelSettings = QuizLevelSetting::where('quiz_id', $request->quiz_id)->first();
        $level = [];
        foreach (json_decode($quizLevelSettings->jumlah_soal_per_level) as $key => $value) {
            $lvlNumber = preg_replace('/[^0-9]/', '', $key);
            $level['fase' . $lvlNumber] = 0;
        }

        $attempt = QuizAttempts::create([
            'quiz_id' => $request->quiz_id,
            'nisn' => $request->nisn,
            'skor' => 0,
            'level_akhir' => $quizLevelSettings->level_awal,
            'fase' => $quizLevelSettings->level_awal,
            'benar' => json_encode($level),
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

    public function getTopFive(Request $request)
    {

        $query = QuizAttempts::with('siswa')
            ->where('quiz_id', $request->quiz_id)
            ->orderByDesc('skor')
            ->take(5)
            ->get();

        $skorMe = QuizAttempts::select('skor')
            ->where('nisn', Auth::user()->siswa->nisn)
            ->orderByDesc('skor')->first();

        return response()->json([
            'skor_me' => $skorMe,
            'data' => $query,
        ]);
    }

    static function getNilai($first)
    {
        $quizId = $first->quiz_id;
        $quizLevelSettings = QuizLevelSetting::where('quiz_id', $quizId)->first();
        $jumlahSoalPerLevel = json_decode($quizLevelSettings->jumlah_soal_per_level, true);
        $totalSkorLevel = 0;

        foreach (json_decode($quizLevelSettings->skor_level) as $key => $value) {
            $totalSkorLevel += $value * (int) $jumlahSoalPerLevel[$key];
        }

        return [
            "total_skor_level" => $totalSkorLevel,
            "kkm" => $quizLevelSettings->kkm,
        ];
    }

    public function getApiQuizGuru(Request $request)
    {
        $query = QuizAttempts::with(['quizzes', 'siswa'])
            ->where(function ($q) use ($request) {
                $q->where('quiz_id', $request->quiz_id);
            })->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas', $request->kelas)
                    ->where('tahun_ajaran', $request->tahun_ajaran);
            })->get();

        $avgScores = $query->map(function ($items) {
            $first = $items->first(); // untuk ambil info nama mapel
            $avg = $items->avg('skor');

            return [
                'nama' => $first->siswa->nama,
                'mapel_id' => $first->quizzes->mataPelajaran->id,
                'mapel' => $first->quizzes->mataPelajaran->nama,
                'skor' => $first->skor,
                'persentase' => round(($avg / $this->getNilai($first)['total_skor_level']) * 100),
                'kkm' => $this->getNilai($first)['kkm'],
            ];
        });

        return $this->okApiResponse($avgScores);
    }

}
