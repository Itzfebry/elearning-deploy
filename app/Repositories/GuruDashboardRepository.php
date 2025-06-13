<?php

namespace App\Repositories;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Materi;
use App\Models\QuizAttempts;
use App\Models\QuizLevelSetting;
use App\Models\Quizzes;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GuruDashboardRepository
{
    public function getDashboardData($tahunAjaran = 'all', $kelas = 'all', $quizStatus = 'all')
    {
        $guru = Auth::user()->guru;
        
        // Get all academic years for filter based on guru's subjects
        $tahunAjaranList = MataPelajaran::where('guru_nip', $guru->nip)
            ->distinct('tahun_ajaran')
            ->pluck('tahun_ajaran')
            ->toArray();

        // Get all classes for filter based on guru's subjects
        $kelasList = MataPelajaran::where('guru_nip', $guru->nip)
            ->distinct('kelas')
            ->pluck('kelas')
            ->toArray();
        
        // Get active academic year
        $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();
        
        // Get relevant MataPelajaran first based on guru_nip and tahun_ajaran
        $guruMataPelajaranQuery = MataPelajaran::where('guru_nip', $guru->nip);

        if ($tahunAjaran !== 'all') {
            $guruMataPelajaranQuery->where('tahun_ajaran', $tahunAjaran);
        }
        if ($kelas !== 'all') {
            $guruMataPelajaranQuery->where('kelas', $kelas);
        }
        $guruMataPelajaranIds = $guruMataPelajaranQuery->pluck('id')->toArray();
        $guruMataPelajaranClasses = $guruMataPelajaranQuery->pluck('kelas')->unique()->toArray();

        // Now build the kelasQuery based on these class names
        $kelasQuery = Kelas::whereIn('nama', $guruMataPelajaranClasses);
        
        // Get subjects taught by this teacher (this query already correctly uses guru_nip)
        $mataPelajaranQuery = MataPelajaran::where('guru_nip', $guru->nip);
        
        // Apply filters if not 'all'
        if ($tahunAjaran !== 'all') {
            $mataPelajaranQuery->where('tahun_ajaran', $tahunAjaran);
        }
        
        if ($kelas !== 'all') {
            $kelasQuery->where('nama', $kelas);
            $mataPelajaranQuery->where('kelas', $kelas);
        }
        
        // Get counts
        $kelasCount = $kelasQuery->count();
        $mataPelajaranCount = $mataPelajaranQuery->count();
        
        // Get total students from these classes
        $siswaCount = Siswa::whereIn('kelas', $kelasQuery->pluck('nama'))->count();

        // New features: Total Tugas, Materi, Quiz
        $totalTugas = Tugas::where('guru_nip', $guru->nip)
            ->when($tahunAjaran !== 'all', function ($query) use ($tahunAjaran) {
                return $query->where('tahun_ajaran', $tahunAjaran);
            })
            ->when($kelas !== 'all', function ($query) use ($kelas) {
                return $query->where('kelas', $kelas);
            })
            ->count();

        $totalMateri = Materi::whereIn('matapelajaran_id', $guruMataPelajaranIds)
            ->when($tahunAjaran !== 'all', function ($query) use ($tahunAjaran) {
                return $query->where('tahun_ajaran', $tahunAjaran);
            })
            ->count();
        
        $totalQuiz = Quizzes::whereIn('matapelajaran_id', $guruMataPelajaranIds)
            ->count();

        // Quiz Results (Passed/Failed)
        $passedQuizzes = 0;
        $failedQuizzes = 0;

        $quizAttempts = QuizAttempts::whereHas('quizzes.mataPelajaran', function ($query) use ($guru, $tahunAjaran, $kelas) {
            $query->where('guru_nip', $guru->nip);
            if ($tahunAjaran !== 'all') {
                $query->where('tahun_ajaran', $tahunAjaran);
            }
            if ($kelas !== 'all') {
                $query->where('kelas', $kelas);
            }
        })->get();

        foreach ($quizAttempts as $attempt) {
            $quizLevelSettings = QuizLevelSetting::where('quiz_id', $attempt->quiz_id)->first();
            if ($quizLevelSettings) {
                $jumlahSoalPerLevel = json_decode($quizLevelSettings->jumlah_soal_per_level, true);
                $totalSkorLevel = 0;
                foreach (json_decode($quizLevelSettings->skor_level) as $key => $value) {
                    $totalSkorLevel += $value * (int) $jumlahSoalPerLevel[$key];
                }

                $persentase = ($totalSkorLevel > 0) ? round(($attempt->skor / $totalSkorLevel) * 100) : 0;
                $kkm = $quizLevelSettings->kkm;

                if ($persentase >= $kkm) {
                    $passedQuizzes++;
                } else {
                    $failedQuizzes++;
                }
            }
        }

        // Prepare data for Mata Pelajaran Distribution Chart
        $mataPelajaranChartLabels = [];
        $mataPelajaranChartData = [];
        $mataPelajaranWithClasses = MataPelajaran::where('guru_nip', $guru->nip)
            ->when($tahunAjaran !== 'all', function ($query) use ($tahunAjaran) {
                return $query->where('tahun_ajaran', $tahunAjaran);
            })
            ->when($kelas !== 'all', function ($query) use ($kelas) {
                return $query->where('kelas', $kelas);
            })
            ->get();

        foreach ($mataPelajaranWithClasses as $mp) {
            $studentsInClass = Siswa::where('kelas', $mp->kelas)->count();
            $mataPelajaranChartLabels[] = $mp->nama; // Assuming 'nama' is the subject name
            $mataPelajaranChartData[] = $studentsInClass; // Count students in the associated class
        }

        // Prepare data for Siswa per Kelas Chart
        $kelasChartLabels = [];
        $kelasChartData = [];
        $guruClasses = Kelas::whereIn('nama', $guruMataPelajaranClasses)
            ->when($kelas !== 'all', function ($query) use ($kelas) {
                return $query->where('nama', $kelas);
            })
            ->get();

        foreach ($guruClasses as $k) {
            $siswaInKelasCount = Siswa::where('kelas', $k->nama)->count();
            $kelasChartLabels[] = $k->nama;
            $kelasChartData[] = $siswaInKelasCount;
        }

        return [
            'selected_year' => $tahunAjaran,
            'selected_kelas' => $kelas,
            'selected_quiz_status' => $quizStatus,
            'tahun_ajaran_list' => $tahunAjaranList,
            'kelas_list' => $kelasList,
            'tahun_ajaran_aktif' => $tahunAjaranAktif ? $tahunAjaranAktif->tahun : '-',
            'kelas' => $kelasCount,
            'mata_pelajaran' => $mataPelajaranCount,
            'siswa' => $siswaCount,
            'total_tugas' => $totalTugas,
            'total_materi' => $totalMateri,
            'total_quiz' => $totalQuiz,
            'passed_quizzes' => $passedQuizzes,
            'failed_quizzes' => $failedQuizzes,
            'mata_pelajaran_chart' => [
                'labels' => $mataPelajaranChartLabels,
                'data' => $mataPelajaranChartData,
            ],
            'kelas_chart' => [
                'labels' => $kelasChartLabels,
                'data' => $kelasChartData,
            ],
        ];
    }
} 