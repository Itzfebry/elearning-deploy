<?php

namespace App\Http\Controllers\RoleGuru;

use App\Exports\RekapExport;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\QuizAttempts;
use App\Models\QuizLevelSetting;
use App\Models\Quizzes;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RekapQuizController extends Controller
{
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

    public function index(Request $request)
    {
        $nip = Auth::user()->guru->nip;
        $matpel = MataPelajaran::where('guru_nip', $nip)->get();
        $judulQuiz = Quizzes::where('judul', $request->judul)->first();
        $kelas = Kelas::all();
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->get();

        $query = QuizAttempts::with(['quizzes', 'siswa'])
            ->whereHas('quizzes', function ($q) use ($request) {
                $q->where('judul', $request->judul);
            })->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas', $request->kelas)
                    ->where('tahun_ajaran', $request->tahun_ajaran);
            })->get();

        $rekap = $query->map(function ($item) use ($request) {
            $nilaiData = $this->getNilai($item);

            return [
                'matapelajaran' => $item->quizzes->mataPelajaran->nama,
                'judul_quiz' => $item->quizzes->judul,
                'nama_siswa' => $item->siswa->nama,
                'tahun_ajaran' => $request->tahun_ajaran,
                'kelas' => $request->kelas,
                'mapel_id' => $item->quizzes->mataPelajaran->id,
                'total_skor' => $item->skor,
                'persentase' => round(($item->skor / $nilaiData['total_skor_level']) * 100),
                'kkm' => $nilaiData['kkm'],
            ];
        });

        if ($request->input('action') === 'download') {
            return Excel::download(new RekapExport($rekap), 'rekap-quiz' . $request->kelas . '.xlsx');
        }

        // Convert $rekap to array to prevent automatic JSON conversion
        $rekapArray = $rekap->toArray();
        return view("pages.role_guru.quiz.rekap-quiz", compact(['matpel', 'judulQuiz', 'kelas', 'tahunAjaran', 'rekapArray']));
    }
}
