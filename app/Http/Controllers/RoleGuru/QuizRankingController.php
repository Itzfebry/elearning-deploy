<?php

namespace App\Http\Controllers\RoleGuru;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempts;
use App\Models\Siswa;
use Illuminate\Http\Request;

class QuizRankingController extends Controller
{
    // GET /quiz/{quizId}/ranking
    public function ranking($quizId)
    {
        // Ambil attempt terbaru per siswa untuk quiz ini
        $latestAttempts = QuizAttempts::where('quiz_id', $quizId)
            ->selectRaw('MAX(id) as id')
            ->groupBy('nisn')
            ->pluck('id');

        $ranking = QuizAttempts::with('siswa')
            ->whereIn('id', $latestAttempts)
            ->get()
            ->map(function($attempt) {
                return [
                    'nama' => $attempt->siswa->nama ?? '-',
                    'nisn' => $attempt->nisn,
                    'skor' => (int) $attempt->skor,
                    'waktu_selesai' => $attempt->updated_at,
                ];
            })
            ->sortByDesc('skor')
            ->values()
            ->take(5);

        return response()->json([
            'success' => true,
            'ranking' => $ranking
        ]);
    }

    // GET /quiz/{quizId}/skor-saya
    public function skorSaya($quizId)
    {
        $nisn = auth()->user()->siswa->nisn ?? null;
        if (!$nisn) {
            return response()->json(['success' => false, 'message' => 'User bukan siswa.']);
        }
        $attempt = QuizAttempts::where('quiz_id', $quizId)
            ->where('nisn', $nisn)
            ->orderByDesc('id')
            ->first();
        if (!$attempt) {
            return response()->json(['success' => false, 'message' => 'Belum ada attempt.']);
        }
        return response()->json([
            'success' => true,
            'skor' => $attempt->skor,
            'waktu_selesai' => $attempt->updated_at,
        ]);
    }
} 