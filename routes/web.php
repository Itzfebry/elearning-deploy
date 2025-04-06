<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;


Route::get('/', [DashboardController::class, 'index']);
Route::get('/login', [AuthController::class, 'index'])->name('login');

// Siswa
Route::get('siswa', [SiswaController::class, 'index'])->name('siswa');
Route::get('siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
Route::get('siswa/edit/{id}', [SiswaController::class, 'edit'])->name('siswa.edit');
