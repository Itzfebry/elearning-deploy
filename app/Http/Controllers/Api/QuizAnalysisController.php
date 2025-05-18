<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempts;
use App\Models\QuizLevelSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizAnalysisController extends Controller
{
    static function getSkor($first)
    {
        $quizId = $first->quiz_id;
        $quizLevelSettings = QuizLevelSetting::where('quiz_id', $quizId)->first();
        $jumlahSoalPerLevel = json_decode($quizLevelSettings->jumlah_soal_per_level, true);
        $totalSkorLevel = 0;

        foreach (json_decode($quizLevelSettings->skor_level) as $key => $value) {
            $totalSkorLevel += $value * (int) $jumlahSoalPerLevel[$key];
        }
        return $totalSkorLevel;
    }

    public function analisis(Request $request)
    {
        $user = Auth::user()->siswa;

        // Ambil semua attempt dengan relasi quizzes dan mata pelajaran
        $attempts = QuizAttempts::where('nisn', $user->nisn)
            ->with(['quizzes.mataPelajaran'])
            ->get();

        // Kelompokkan berdasarkan mata_pelajaran_id dan hitung rata-rata skor
        $grouped = $attempts->groupBy(function ($item) {
            return $item->quizzes->mataPelajaran->id;
        });

        $avgScores = $grouped->map(function ($items) {
            $first = $items->first(); // untuk ambil info nama mapel
            $avg = $items->avg('skor');

            return [
                'mapel_id' => $first->quizzes->mataPelajaran->id,
                'mapel' => $first->quizzes->mataPelajaran->nama,
                'rata_rata_skor' => $avg,
                'persentase' => round(($avg / $this->getSkor($first)) * 100),
            ];
        });

        // Urutkan berdasarkan skor rata-rata (desc dan asc)
        $kelebihan = $avgScores->sortByDesc('rata_rata_skor')->take(2);
        $kelebihanMapelIds = $kelebihan->pluck('mapel_id')->toArray();

        $kekurangan = $avgScores->whereNotIn('mapel_id', $kelebihanMapelIds)
            ->sortBy('rata_rata_skor')
            ->take(2);

        return response()->json([
            'kelebihan' => array_values($kelebihan->toArray()),
            'kekurangan' => array_values($kekurangan->toArray())
        ]);
    }


}
