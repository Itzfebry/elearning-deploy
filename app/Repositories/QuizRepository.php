<?php

namespace App\Repositories;

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
            'question_id' => 'required|exists:quiz_questions,id',
            'jawaban_siswa' => 'required|in:a,b,c,d',
        ]);

        $attempt = QuizAttempts::findOrFail($attempt_id);
        $question = QuizQuestions::findOrFail($request->question_id);
        $quizLevelSettings = QuizLevelSetting::where('quiz_id', $attempt->quiz_id)->first();

        $jumlahSoalPerLevel = json_decode($quizLevelSettings->jumlah_soal_per_level, true);
        $batasNaikLevel = json_decode($quizLevelSettings->batas_naik_level, true);

        $isCorrect = $request->jawaban_siswa === $question->jawaban_benar ? 1 : 0;

        // Hitung skor tambahan
        $skor_tambahan = 0;
        if ($isCorrect) {
            $skor_tambahan = $question->skor;
        }

        // Tambahkan skor ke attempt
        $attempt->skor += $skor_tambahan;

        // Simpan jawaban
        QuizAttemptAnswers::create([
            'attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'jawaban_siswa' => $request->jawaban_siswa,
            'benar' => $isCorrect,
        ]);

        // Update jumlah soal dijawab
        $attempt->jumlah_soal_dijawab++;

        // cek fase
        foreach ($jumlahSoalPerLevel as $key => $value) {
            $lvlNumber = preg_replace('/[^0-9]/', '', $key); // Ambil angka dari key 'level1', 'level2', dst

            if ($attempt->fase == $lvlNumber) {

                // Inisialisasi nilai benar_fase jika belum ada (opsional jika null)
                $benarKey = 'benar_fase' . $lvlNumber;
                if (!isset($attempt->$benarKey)) {
                    $attempt->$benarKey = 0;
                }

                // Tambah nilai benar jika jawaban benar
                if ($isCorrect) {
                    $attempt->$benarKey++;
                }

                $batasKey = 'batas_naik_level_fase' . $lvlNumber;
                $batasNaik = $quizLevelSettings->$batasKey ?? null;

                if ($batasNaik && $attempt->jumlah_soal_dijawab == $jumlahSoalPerLevel[$key]) {
                    // Cek apakah bisa naik level
                    if ($attempt->$benarKey >= $batasNaik) {
                        $attempt->level_akhir = $lvlNumber + 1;
                    } else {
                        $attempt->level_akhir = $lvlNumber;
                    }

                    // Pindah ke fase berikutnya
                    $attempt->fase = $lvlNumber + 1;
                    $attempt->jumlah_soal_dijawab = 0;
                }
            }
        }


        // Cek di fase berapa
        // if ($attempt->fase == 1) {
        //     if ($isCorrect) {
        //         $attempt->benar_fase1++;
        //     }

        //     if ($attempt->jumlah_soal_dijawab == 7) {
        //         if ($attempt->benar_fase1 >= 4) {
        //             $attempt->level_akhir = 2; // naik ke Sedang
        //         } else {
        //             $attempt->level_akhir = 1; // tetap Mudah
        //         }
        //         $attempt->fase = 2;
        //         $attempt->jumlah_soal_dijawab = 0;
        //     }

        // } elseif ($attempt->fase == 2) {
        //     if ($isCorrect) {
        //         $attempt->benar_fase2++;
        //     }

        //     if ($attempt->jumlah_soal_dijawab == 7) {
        //         if ($attempt->benar_fase2 >= 5) {
        //             $attempt->level_akhir = 3; // naik ke Susah
        //         } else {
        //             $attempt->level_akhir = 2; // tetap di Sedang
        //         }
        //         $attempt->fase = 3;
        //         $attempt->jumlah_soal_dijawab = 0;
        //     }

        // } elseif ($attempt->fase == 3) {
        //     // Tidak ada perubahan level
        // }

        $attempt->save();

        $jumlah_jawaban = QuizAttemptAnswers::where('attempt_id', $attempt->id)->count();
        $total_soal_tampil = $attempt->quizzes->total_soal_tampil ?? 20; // default 20 jika null
        $selesai = $jumlah_jawaban + 1 >= $total_soal_tampil;

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
        $attempt = QuizAttempts::where('quiz_id', $quizId)->first();
        return $attempt;
    }

}