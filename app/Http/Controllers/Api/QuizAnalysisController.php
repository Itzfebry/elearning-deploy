<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizAnalysisController extends Controller
{
    public function analisis(Request $request)
    {
        $user = Auth::user()->siswa;

        $attempts = QuizAttempts::where('nisn', $user->nisn)
            ->with(['quizzes.mataPelajaran'])
            ->get();

        // Ambil 2 skor tertinggi
        $kelebihan = $attempts->sortByDesc('skor')
            ->take(2);

        // Simpan ID quiz yang sudah dipakai di kelebihan
        $excludedIds = $kelebihan->pluck('id')->toArray();

        // Ambil 2 skor terendah
        $kekurangan = $attempts->whereNotIn('id', $excludedIds)
            ->sortBy('skor')
            ->take(2);

        $kelebihanFormatted = $kelebihan->map(function ($item) {
            return [
                'mapel' => $item->quizzes->mataPelajaran->nama,
                'skor' => $item->skor,
                'persentase' => ((int) $item->skor / 195) * 100,
            ];
        })->values();

        $kekuranganFormatted = $kekurangan->map(function ($item) {
            return [
                'mapel' => $item->quizzes->mataPelajaran->nama,
                'skor' => $item->skor,
                'persentase' => ((int) $item->skor / 195) * 100,
            ];
        })->values();

        return response()->json([
            'kelebihan' => $kelebihanFormatted,
            'kekurangan' => $kekuranganFormatted
        ]);
    }

}
