<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasContoller;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\RoleGuru\MateriController;
use App\Http\Controllers\RoleGuru\QuizController;
use App\Http\Controllers\RoleGuru\RekapQuizController;
use App\Http\Controllers\RoleGuru\RekapTugasController;
use App\Http\Controllers\RoleGuru\TugasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\GuruDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\SubmitTugasController;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role == 'admin') {
            return redirect()->route('dashboard.admin');
        } elseif (Auth::user()->role == 'guru') {
            return redirect()->route('dashboard.guru');
        }
    }
    return redirect()->route('login');
})->name('root');

Route::middleware(["guest", "web"])->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.action');
});

Route::middleware(["auth", "web"])->group(function () {
    Route::get('/change-password', [UserController::class, 'change'])->name('change-password');
    Route::post('/change-password/store', [UserController::class, 'changePassword'])->name('change-password.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(["auth", "role:admin"])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard.admin');

    // Siswa
    Route::get('siswa', [SiswaController::class, 'index'])->name('siswa');
    Route::get('siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
    Route::post('siswa/store', [SiswaController::class, 'store'])->name('siswa.store');
    Route::get('siswa/edit/{id}', [SiswaController::class, 'edit'])->name('siswa.edit');
    Route::put('siswa/update/{id}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::post('siswa/delete', [SiswaController::class, 'destroy'])->name('siswa.delete');

    // guru
    Route::get('guru', [GuruController::class, 'index'])->name('guru');
    Route::get('guru/create', [GuruController::class, 'create'])->name('guru.create');
    Route::post('guru/store', [GuruController::class, 'store'])->name('guru.store');
    Route::get('guru/edit/{id}', [GuruController::class, 'edit'])->name('guru.edit');
    Route::put('guru/update/{id}', [GuruController::class, 'update'])->name('guru.update');
    Route::post('guru/delete', [GuruController::class, 'destroy'])->name('guru.delete');

    // admin
    Route::get('admin', [AdminController::class, 'index'])->name('admin');
    Route::get('admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('admin/update/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::post('admin/delete', [AdminController::class, 'destroy'])->name('admin.delete');
    Route::get('admin/pindah-data', [App\Http\Controllers\AdminController::class, 'pindahDataPage'])->name('admin.pindah-data');
    Route::post('admin/pindah-data', [App\Http\Controllers\AdminController::class, 'pindahGuruMatpel'])->name('admin.pindah-data.proses');
    Route::get('admin/audit-log', [App\Http\Controllers\AdminController::class, 'auditLogPage'])->name('admin.audit-log');

    // kelas
    Route::get('kelas', [KelasContoller::class, 'index'])->name('kelas');
    Route::get('kelas/create', [KelasContoller::class, 'create'])->name('kelas.create');
    Route::post('kelas/store', [KelasContoller::class, 'store'])->name('kelas.store');
    // Route::get('kelas/edit/{id}', [KelasContoller::class, 'edit'])->name('kelas.edit');
    // Route::put('kelas/update/{id}', [KelasContoller::class, 'update'])->name('kelas.update');
    Route::post('kelas/delete', [KelasContoller::class, 'destroy'])->name('kelas.delete');

    // Wali Kelas
    Route::get('wali-kelas', [WaliKelasController::class, 'index'])->name('wali-kelas');
    Route::get('wali-kelas/create', [WaliKelasController::class, 'create'])->name('wali-kelas.create');
    Route::post('wali-kelas/store', [WaliKelasController::class, 'store'])->name('wali-kelas.store');
    Route::get('wali-kelas/edit/{id}', [WaliKelasController::class, 'edit'])->name('wali-kelas.edit');
    Route::put('wali-kelas/update/{id}', [WaliKelasController::class, 'update'])->name('wali-kelas.update');
    Route::post('wali-kelas/delete', [WaliKelasController::class, 'destroy'])->name('wali-kelas.delete');

    // Tahun Ajaran
    Route::get('tahun-ajaran', [TahunAjaranController::class, 'index'])->name('tahun-ajaran');
    Route::get('tahun-ajaran/create', [TahunAjaranController::class, 'create'])->name('tahun-ajaran.create');
    Route::post('tahun-ajaran/store', [TahunAjaranController::class, 'store'])->name('tahun-ajaran.store');
    Route::get('/tahun-ajaran/edit', [TahunAjaranController::class, 'edit'])->name('tahun-ajaran.edit');
    Route::put('/tahun-ajaran/update', [TahunAjaranController::class, 'update'])->name('tahun-ajaran.update');
    Route::post('tahun-ajaran/delete', [TahunAjaranController::class, 'destroy'])->name('tahun-ajaran.delete');

    // Mata Pelajaran
    Route::get('mata-pelajaran', [MataPelajaranController::class, 'index'])->name('mata-pelajaran');
    Route::get('mata-pelajaran/create', [MataPelajaranController::class, 'create'])->name('mata-pelajaran.create');
    Route::post('mata-pelajaran/store', [MataPelajaranController::class, 'store'])->name('mata-pelajaran.store');
    Route::get('mata-pelajaran/edit/{id}', [MataPelajaranController::class, 'edit'])->name('mata-pelajaran.edit');
    Route::put('mata-pelajaran/update/{id}', [MataPelajaranController::class, 'update'])->name('mata-pelajaran.update');
});

Route::middleware(["auth", "role:guru"])->group(function () {
    Route::get('/guru/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard.guru');

    // Mata Pelajaran
    Route::get('materi', [MateriController::class, 'index'])->name('materi');
    Route::get('materi/create', [MateriController::class, 'create'])->name('materi.create');
    Route::post('materi/store', [MateriController::class, 'store'])->name('materi.store');
    Route::get('materi/detail/{id}', [MateriController::class, 'detail'])->name('materi.detail');
    Route::get('materi/edit/{id}', [MateriController::class, 'edit'])->name('materi.edit');
    Route::put('materi/update/{id}', [MateriController::class, 'update'])->name('materi.update');
    Route::post('materi/delete', [MateriController::class, 'destroy'])->name('materi.delete');

    // Tugas
    Route::get('tugas', [TugasController::class, 'index'])->name('tugas');
    Route::get('tugas/create', [TugasController::class, 'create'])->name('tugas.create');
    Route::post('tugas/store', [TugasController::class, 'store'])->name('tugas.store');
    Route::get('tugas/edit/{id}', [TugasController::class, 'edit'])->name('tugas.edit');
    Route::put('tugas/update/{id}', [TugasController::class, 'update'])->name('tugas.update');
    Route::post('tugas/delete', [TugasController::class, 'destroy'])->name('tugas.delete');

    // Quiz
    Route::get('quiz', [QuizController::class, 'index'])->name('quiz');
    Route::get('/get-quiz-by-matpel/{id}', [QuizController::class, 'getQuizByMatpel']);
    Route::get('quiz/create', [QuizController::class, 'create'])->name('quiz.create');
    Route::post('quiz/store', [QuizController::class, 'store'])->name('quiz.store');
    Route::get('quiz/excel_download', [QuizController::class, 'excelDownload'])->name('quiz.excel.download');
    Route::post('quiz/preview', [QuizController::class, 'preview'])->name('quiz.preview');
    Route::get('/quiz/reset-preview', [QuizController::class, 'resetPreview'])->name('quiz.preview.reset');
    Route::post('quiz/delete', [QuizController::class, 'destroy'])->name('quiz.delete');

    Route::get('rekap-quiz', [RekapQuizController::class, 'index'])->name('rekap.quiz');

    // Rekap Tugas
    Route::get('rekap-tugas', [RekapTugasController::class, 'index'])->name('rekap.tugas');
    Route::get('rekap-tugas/detail/{id}', [RekapTugasController::class, 'detail'])->name('rekap.tugas.detail');
    Route::post('rekap-tugas/nilai/{id}', [RekapTugasController::class, 'updateNilai'])->name('rekap.tugas.nilai');
    Route::get('rekap-tugas/export', [RekapTugasController::class, 'export'])->name('rekap.tugas.export');
    Route::get('rekap-tugas/export-detail', [RekapTugasController::class, 'exportDetail'])->name('rekap.tugas.export.detail');
});

// Route untuk API web (tanpa authentication)
Route::get('/api/web/get-detail-submit-tugas', [SubmitTugasController::class, 'detail'])->name('api.web.submit-tugas.detail');