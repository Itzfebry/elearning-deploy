<?php   

namespace App\Repositories;

use App\Http\Controllers\Traits\ApiResponse;
use App\Models\QuizAttemptAnswers;
use App\Models\QuizAttempts;
use App\Models\QuizLevelSetting;
use App\Models\QuizQuestions;
use App\Models\Quizzes;
use Illuminate\Support\Facades\Auth;

class QuizRepository
{
    protected $model;
    protected $modelQuizzes;

    use ApiResponse;

    public function __construct(QuizQuestions $quizQuestions, Quizzes $quizzes)
    {
        $this->model = $quizQuestions;
        $this->modelQuizzes = $quizzes;
    }

    public function apiGetQuizzes($id)
    {
        $nisn = Auth::user()->siswa->nisn;
        $query = Quizzes::where('matapelajaran_id', $id)
            ->with([
                'quizAttempt' => function ($q) use ($nisn) {
                    $q->where('nisn', $nisn);
                }
            ])
            ->get();

        return $query;
    }

    public function apiGetQuizzesGuru($request, $id)
    {
        $query = Quizzes::where('matapelajaran_id', $id)
            ->whereHas('mataPelajaran', function ($q) use ($request) {
                $q->where('kelas', $request->kelas)
                    ->where('tahun_ajaran', $request->tahun_ajaran);
            })
            ->get();

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

        // Ambil total soal tampil dari quiz
        $quiz = Quizzes::findOrFail((int) $attempt->quiz_id);

        // Kalau sudah mencapai total soal tampil, jangan kasih soal lagi
        if ($attempt->jumlahJawaban() >= $quiz->total_soal_tampil) {
            return response()->json([
                'message' => 'Semua soal sudah dijawab.',
            ], 200);
        }

        // Kalau belum, ambil 1 soal random di level sekarang
        // Find the attempt by given ID
        $question = QuizQuestions::where('quiz_id', $attempt->quiz_id)
            ->where('level', $attempt->level_akhir)
            // Get the total soal tampil from the quiz
            ->whereDoesntHave('attemptAnswers', function ($q) use ($attempt_id) {
                $q->where('attempt_id', $attempt_id);
            })
            ->inRandomOrder()
            ->first();

        $quizAttemptAnswer = QuizAttemptAnswers::where('attempt_id', $attempt_id)->count();

        if (!$question) {
            return response()->json([
                'message' => 'Tidak ada soal lagi di level ini.',
            ], 404);
        }

        $question['pertanyaan'] = $quizAttemptAnswer + 1 . ". " . $question->pertanyaan;

        return $question;
    }

    public function answer($request, $attempt_id)
    {
        $request->validate([
            'question_id' => 'required|integer',
            // Hilangkan validasi in:a,b,c,d agar jawaban_siswa boleh kosong
            'jawaban_siswa' => 'nullable',
        ]);

        // Validasi attempt
        $attempt = QuizAttempts::findOrFail($attempt_id);
        
        // Validasi question exists dan belongs to the correct quiz
        $question = QuizQuestions::where('id', $request->question_id)
            ->where('quiz_id', $attempt->quiz_id)
            ->first();
            
        if (!$question) {
            throw new \Exception('The selected question is invalid or does not belong to this quiz.');
        }

        $quizzes = Quizzes::findOrFail($attempt->quiz_id);
        $quizLevelSettings = QuizLevelSetting::where('quiz_id', $attempt->quiz_id)->first();

        $jumlahSoalPerLevel = json_decode($quizLevelSettings->jumlah_soal_per_level, true);
        $batasNaikLevel = json_decode($quizLevelSettings->batas_naik_level, true);

        // Jika jawaban kosong, otomatis salah
        $isCorrect = ($request->jawaban_siswa && $request->jawaban_siswa == $question->jawaban_benar) ? 1 : 0;

        // Hitung skor tambahan
        $skor_tambahan = 0;
        if ($isCorrect) {
            $skor_tambahan = $question->skor;
        }

        // Tambahkan skor ke attempt
        $attempt->skor += $skor_tambahan;

        // Simpan jawaban (jika kosong, simpan string kosong)
        QuizAttemptAnswers::create([
            'attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'jawaban_siswa' => $request->jawaban_siswa ?? '',
            'benar' => $isCorrect,
        ]);

        // Update jumlah soal dijawab
        $attempt->jumlah_soal_dijawab++;

        // cek fase
        foreach ($jumlahSoalPerLevel as $key => $value) {
            $lvlNumber = preg_replace('/[^0-9]/', '', $key);


            if ($attempt->fase == $lvlNumber) {
                $benar = json_decode($attempt->benar, true) ?? [];
                $jumlahFase = count($jumlahSoalPerLevel);
                $isLastFase = $attempt->fase == $jumlahFase;

                if (!$isLastFase && $isCorrect) {
                    $benar['fase' . $lvlNumber] = ($benar['fase' . $lvlNumber] ?? 0) + ($isCorrect ? 1 : 0);
                }

                $attempt->benar = json_encode($benar);

                if ($attempt->jumlah_soal_dijawab == $value) {
                    if ($benar['fase' . $lvlNumber] >= $batasNaikLevel['fase' . $lvlNumber]) {
                        // if ($attempt->level_akhir < $lvlNumber) {
                        $attempt->level_akhir += 1;
                        // }
                    } else {
                        $attempt->level_akhir;
                    }

                    $attempt->fase += 1;
                    $attempt->jumlah_soal_dijawab = 0;
                }
                break;
            }
        }

        $attempt->save();

        $jumlah_jawaban = QuizAttemptAnswers::where('attempt_id', $attempt->id)->count();
        $total_soal_tampil = $quizzes->total_soal_tampil;
        $selesai = $jumlah_jawaban >= $total_soal_tampil;

        return [
            'quiz_id' => $attempt->quiz_id,
            'correct' => $isCorrect,
            'fase' => $attempt->fase,
            'new_level' => $attempt->level_akhir,
            'skor_sementara' => $attempt->skor,
            'selesai' => $selesai,
        ];
    }

    public function getFinishQuiz($quizId)
    {
        $attempt = QuizAttempts::where('quiz_id', $quizId)
            ->where('nisn', Auth::user()->siswa->nisn)
            ->orderByDesc('id') // ambil attempt terbaru
            ->first();

        if (!$attempt) {
            return $this->errorApiResponse('Data tidak ditemukan', 404);
        }

        $attempt->jumlah_soal = $attempt->quizzes()->first()->total_soal_tampil;
        $attempt->jawaban_benar = (string) QuizAttemptAnswers::where('attempt_id', $attempt->id)->where('benar', 1)->count();
        $attempt->jawaban_salah = (string) QuizAttemptAnswers::where('attempt_id', $attempt->id)->where('benar', "0")->count();

        return $this->okApiResponse($attempt);
    }
}