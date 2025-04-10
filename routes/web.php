<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasContoller;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\WaliKelasController;
use Illuminate\Support\Facades\Route;


Route::middleware(["guest", "web"])->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.action');
});

Route::middleware(["auth", "web"])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index']);

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

    // kelas
    Route::get('kelas', [KelasContoller::class, 'index'])->name('kelas');
    Route::get('kelas/create', [KelasContoller::class, 'create'])->name('kelas.create');
    Route::post('kelas/store', [KelasContoller::class, 'store'])->name('kelas.store');
    // Route::get('kelas/edit/{id}', [KelasContoller::class, 'edit'])->name('kelas.edit');
    // Route::put('kelas/update/{id}', [KelasContoller::class, 'update'])->name('kelas.update');
    Route::post('kelas/delete', [KelasContoller::class, 'destroy'])->name('kelas.delete');

    Route::get('wali-kelas', [WaliKelasController::class, 'index'])->name('wali-kelas');
    Route::get('wali-kelas/create', [WaliKelasController::class, 'create'])->name('wali-kelas.create');
    Route::get('wali-kelas/edit/{id}', [WaliKelasController::class, 'edit'])->name('wali-kelas.edit');
    Route::put('wali-kelas/update/{id}', [WaliKelasController::class, 'update'])->name('wali-kelas.update');
});

