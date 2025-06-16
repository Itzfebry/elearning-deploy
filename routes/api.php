<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KelasContoller;
use App\Http\Controllers\Api\MataPelajaranController;
use App\Http\Controllers\Api\MateriController;
use App\Http\Controllers\Api\QuizAnalysisController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\SubmitTugasController;
use App\Http\Controllers\Api\TahunAjaranController;
use App\Http\Controllers\Api\TugasController;
use App\Http\Controllers\SiswaNotifikasiController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckApiToken;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    // User
    Route::get('/get-me', [AuthController::class, 'user'])->name('get.me');

    // Materi 
    Route::get('/get-materi', [MateriController::class, 'getMateri'])->name('get.materi');

    // Tugas 
    Route::get('/get-tugas', [TugasController::class, 'getTugas'])->name('get.tugas');
    Route::get('/get-submit-tugas-siswa', [TugasController::class, 'getSubmitTugasSiswa']);

    // mata Pelajaran 
    Route::get('/get-mata-pelajaran', [MataPelajaranController::class, 'getMatpel'])->name('get.mataMataPelajaran');
    Route::get('/get-mata-pelajaran-simple', [MataPelajaranController::class, 'getMatpelSimple'])->name('get.mataMataPelajaranSimple');

    // submit tugas
    Route::post('/submit-tugas', [SubmitTugasController::class, 'store']);
    Route::get('/get-detail-submit-tugas', [SubmitTugasController::class, 'detail']);
    Route::post('/update-tugas', [SubmitTugasController::class, 'update']);
    Route::put('/submit-tugas/nilai', [SubmitTugasController::class, 'updateNilai']);

    // Kelas
    Route::get('/kelas', [KelasContoller::class, 'index']);
    // Tahun Ajaran
    Route::get('/tahun-ajaran', [TahunAjaranController::class, 'getTahunAjaran']);

    // Quiz
    Route::get('/quiz', [QuizController::class, 'index']);
    Route::get('/quiz-guru', [QuizController::class, 'quizGuru']);
    Route::post('/quiz-attempts/start', [QuizController::class, 'start']);
    Route::get('/quiz-attempts/{attempt}/next-question', [QuizController::class, 'nextQuestion']);
    Route::post('/quiz-attempts/{attempt}/answer', [QuizController::class, 'answer']);
    Route::get('/quiz-attempts/finish', [QuizController::class, 'getFinishQuiz']);
    Route::get('/quiz-top-five', [QuizController::class, 'getTopFive']);
    Route::get('/get-quiz-attempt-guru', [QuizController::class, 'getApiQuizGuru']);
    Route::get('/quiz-attempts/{attempt}/debug', [QuizController::class, 'debugQuiz']);

    // Notifikasi
    Route::get('/siswa/notifikasi/count', [SiswaNotifikasiController::class, 'notifCount']);
    Route::get('/siswa/notifikasi', [SiswaNotifikasiController::class, 'index']);
    Route::post('/siswa/notifikasi/{id}/baca', [SiswaNotifikasiController::class, 'markAsRead']);

    // Change Password
    Route::post('/change-password', [UserController::class, 'changePasswordApi']);

    // Analysis Siswa
    Route::get('/analysis-siswa', [QuizAnalysisController::class, 'analisis']);
});

Route::middleware(CheckApiToken::class)->group(function () {
    Route::get('/check-token', function (Request $request) {
        return response()->json(['message' => 'Token valid']);
    });
});