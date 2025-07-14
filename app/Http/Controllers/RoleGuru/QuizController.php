<?php

namespace App\Http\Controllers\RoleGuru;

use App\Exports\QuizExport;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\QuizLevelSetting;
use App\Models\QuizQuestions;
use App\Models\Quizzes;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Notifications\QuizBaruNotification;
use App\Repositories\QuizRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class QuizController extends Controller
{
    protected $param;

    public function __construct(QuizRepository $quiz)
    {
        $this->param = $quiz;
    }

    public function index(Request $request)
    {
        $nip = Auth::user()->guru->nip;
        $matpel = MataPelajaran::where('guru_nip', $nip)->get();
        $kelas = Kelas::all();
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->get();

        // Get quizzes created by this teacher
        $quiz = Quizzes::with(['mataPelajaran', 'quizLevelSetting'])
            ->whereHas('mataPelajaran', function ($q) use ($nip) {
                $q->where('guru_nip', $nip);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view("pages.role_guru.quiz.index", compact(['matpel', 'kelas', 'tahunAjaran', 'quiz']));
    }

    public function getQuizByMatpel($id)
    {
        $nip = Auth::user()->guru->nip;
        $quizzes = Quizzes::where('matapelajaran_id', $id)
            ->whereHas('mataPelajaran', function ($q) use ($nip) {
                $q->where('guru_nip', $nip);
            })
            ->get();

        return response()->json($quizzes);
    }


    public function excelDownload()
    {
        return Excel::download(new QuizExport, "format_excel_untuk_quiz.xlsx");
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $data = Excel::toArray([], $request->file('file'));

        // Ambil sheet pertama
        $rows = $data[0];

        $filteredRows = [];
        $jumlahSoalPerLevel = [];
        $totalSoalPeLevel = [];
        $batasNaikLevel = [];
        $skorLevel = [];

        foreach ($rows as $index => $row) {
            if ($index == 0 || !empty($row[1])) {
                $filteredRows[] = $row;

                $level = $row[3];
                $skor = $row[8];
                if (!empty($level) && $index != 0) {
                    $key = 'level' . $level;
                    if (!isset($jumlahSoalPerLevel[$key])) {
                        $jumlahSoalPerLevel[$key] = 0;
                        $totalSoalPeLevel[$key] = 0;
                    }
                    $jumlahSoalPerLevel[$key]++;
                    $totalSoalPeLevel[$key]++;
                    $skorLevel[$key] = $skor;
                }
            }
        }

        // Hitung batas naik level = 50% dari jumlah soal di level tersebut
        foreach ($jumlahSoalPerLevel as $key => $jumlah) {
            // Ambil level dari key, contoh: 'level2' → 2
            $level = str_replace('level', '', $key);
            $keyFase = 'fase' . $level;

            // Hitung 50% lalu dibulatkan ke atas (ceil)
            $batasNaikLevel[$keyFase] = (int) ceil($jumlah * 0.5);

            $jumlahSoalPerLevel[$key] = (int) ceil($jumlah * 0.5);
        }


        $soalCount = count($filteredRows) - 1;

        // Simpan preview dan jumlah soal ke session
        Session::put('judul', $request->judul);
        Session::put('deskripsi', $request->deskripsi);
        Session::put('matapelajaran_id', $request->matapelajaran_id);
        Session::put('waktu', $request->waktu);
        Session::put('preview_soal', $filteredRows);
        Session::put('total_soal', $soalCount);
        Session::put('total_soal_tampil', $request->total_soal_tampil ?? 20);
        Session::put('uploaded_filename', $request->file('file')->getClientOriginalName());

        // quiz level settings
        Session::put('total_soal_per_level', $totalSoalPeLevel);
        Session::put('jumlah_soal_per_level', $jumlahSoalPerLevel);
        Session::put('level_awal', $request->level_awal);
        Session::put('batas_naik_level', $batasNaikLevel);
        Session::put('skor_level', $skorLevel);
        Session::put('kkm', $request->kkm);

        return redirect()->back();
    }

    protected function removeSession()
    {
        session()->forget('judul');
        session()->forget('deskripsi');
        session()->forget('matapelajaran_id');
        session()->forget('waktu');
        session()->forget('preview_soal');
        session()->forget('total_soal');
        session()->forget('total_soal_tampil');
        session()->forget('uploaded_filename');

        // quiz level settings
        session()->forget('total_soal_per_level');
        session()->forget('jumlah_soal_per_level');
        session()->forget('level_awal');
        session()->forget('batas_naik_level');
        session()->forget('skor_level');
        session()->forget('kkm');
    }

    public function resetPreview()
    {
        $this->removeSession();

        return redirect()->back()->with('success', 'Data preview berhasil direset.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nip = Auth::user()->guru->nip;
        $matpel = MataPelajaran::where("guru_nip", $nip)->get();
        $naik_level = json_encode(session('batas_naik_level') ?? []);
        return view("pages.role_guru.quiz.create", compact(['matpel', 'naik_level']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $preview = session('preview_soal');
        if (!is_array($preview)) {
            return redirect()->back()->with('validation_error', [
                'title' => 'Error',
                'message' => 'Data soal tidak ditemukan atau session sudah habis. Silakan ulangi proses import/preview quiz.'
            ]);
        }
        array_shift($preview);

        if (!$preview || count($preview) <= 1) {
            \Log::error('Quiz Import Error: Tidak ada data untuk disimpan');
            Alert::error("Terjadi Kesalahan", "Tidak ada data untuk disimpan.");
            return redirect()->back();
        }

        if ($request->total_soal_tampil < 10) {
            \Log::error('Quiz Import Error: Jumlah soal minimal 10');
            Alert::error("Terjadi Kesalahan", "Jumlah soal minimal 10.");
            return redirect()->back();
        }

        // ========================================
        // VALIDASI KETAT UNTUK MENCEGAH BUG QUIZ
        // ========================================

        // 1. VALIDASI: Total soal per level harus sama dengan total soal tampil
        $totalSoalPerLevel = 0;
        foreach ($request->jumlah_soal_per_level as $key => $value) {
            $totalSoalPerLevel += (int) $value;
        }

        if ($totalSoalPerLevel != $request->total_soal_tampil) {
            \Log::error('Quiz Import Error: Total soal per level tidak sama dengan total soal tampil');
            \Log::error('Detail: Total soal per level = ' . $totalSoalPerLevel . ', Total soal tampil = ' . $request->total_soal_tampil);
            \Log::error('Detail per level: ' . json_encode($request->jumlah_soal_per_level));
            
            return redirect()->back()->with('validation_error', [
                'title' => 'Error Validasi',
                'message' => "Total soal per level ($totalSoalPerLevel) harus sama dengan total soal tampil ({$request->total_soal_tampil}).<br><br><strong>POTENSI BUG:</strong> Jika tidak sama, siswa bisa stuck di level tertentu karena soal tidak cukup.<br><strong>CATATAN:</strong> Pastikan jumlah soal per level dijumlahkan sama dengan total soal tampil."
            ]);
        }

        // 2. VALIDASI: Hitung jumlah soal yang tersedia per level dari data import
        $levelCounts = [];
        foreach ($preview as $row) {
            if (!empty($row[3])) { // level
                $level = $row[3];
                $levelCounts[$level] = ($levelCounts[$level] ?? 0) + 1;
            }
        }

        // 3. VALIDASI: Jumlah soal per level tidak boleh melebihi soal yang tersedia
        foreach ($request->jumlah_soal_per_level as $key => $value) {
            $level = str_replace('level', '', $key);
            $availableInLevel = $levelCounts[$level] ?? 0;
            
            if ($value > $availableInLevel) {
                \Log::error('Quiz Import Error: Jumlah soal setting melebihi soal yang tersedia');
                \Log::error('Detail: Level ' . $level . ' - Setting: ' . $value . ', Tersedia: ' . $availableInLevel);
                
                return redirect()->back()->with('validation_error', [
                    'title' => 'Error Validasi',
                    'message' => "Level $level: Setting $value soal, tapi hanya ada $availableInLevel soal tersedia.<br><br><strong>POTENSI BUG:</strong> Sistem akan stuck karena tidak ada cukup soal di level tersebut.<br><strong>CATATAN:</strong> Kurangi jumlah soal setting atau tambah soal di level tersebut."
                ]);
            }
        }

        // 4. VALIDASI: Batas naik level tidak boleh melebihi jumlah soal di level tersebut
        foreach ($request->batas_naik_level as $key => $value) {
            $level = str_replace('fase', '', $key);
            $soalInLevel = $request->jumlah_soal_per_level["level$level"] ?? 0;
            
            // VALIDASI BARU: Batas naik level tidak boleh sama atau lebih besar dari jumlah soal di level
            if ($value >= $soalInLevel) {
                return redirect()->back()->with('validation_error', [
                    'title' => 'Error Validasi',
                    'message' => "Level $level: Syarat naik level ($value) tidak boleh sama atau lebih besar dari jumlah soal ($soalInLevel).<br><br><strong>POTENSI BUG:</strong> Jika siswa salah satu saja, quiz akan stuck di level ini.<br><strong>CATATAN:</strong> Kurangi syarat naik level atau tambah jumlah soal di level ini."
                ]);
            }
            
            if ($value > $soalInLevel) {
                \Log::error('Quiz Import Error: Batas naik level melebihi jumlah soal di level');
                \Log::error('Detail: Level ' . $level . ' - Batas naik: ' . $value . ', Soal di level: ' . $soalInLevel);
                return redirect()->back()->with('validation_error', [
                    'title' => 'Error Validasi',
                    'message' => "Level $level: Batas naik level ($value) tidak boleh melebihi jumlah soal ($soalInLevel).<br><br><strong>POTENSI BUG:</strong> Siswa tidak akan pernah naik level karena batas terlalu tinggi.<br><strong>CATATAN:</strong> Batas naik level harus ≤ jumlah soal di level tersebut."
                ]);
            }
        }

        // 5. VALIDASI: Pastikan ada soal di setiap level yang di-setting
        foreach ($request->jumlah_soal_per_level as $key => $value) {
            $level = str_replace('level', '', $key);
            $availableInLevel = $levelCounts[$level] ?? 0;
            
            if ($availableInLevel == 0) {
                \Log::error('Quiz Import Error: Tidak ada soal di level yang di-setting');
                \Log::error('Detail: Level ' . $level . ' tidak memiliki soal di data import');
                
                return redirect()->back()->with('validation_error', [
                    'title' => 'Error Validasi',
                    'message' => "Level $level: Tidak ada soal tersedia di level ini.<br><br><strong>POTENSI BUG:</strong> Sistem akan stuck karena tidak ada soal di level tersebut.<br><strong>CATATAN:</strong> Pastikan data import memiliki soal dengan level $level atau hapus setting level ini."
                ]);
            }
        }

        // 6. VALIDASI: Level harus berurutan (1, 2, 3, dst)
        $levels = array_keys($request->jumlah_soal_per_level);
        sort($levels);
        $expectedLevels = [];
        for ($i = 1; $i <= count($levels); $i++) {
            $expectedLevels[] = "level$i";
        }
        
        if ($levels !== $expectedLevels) {
            \Log::error('Quiz Import Error: Level tidak berurutan');
            \Log::error('Detail: Level yang ada = ' . json_encode($levels) . ', Level yang diharapkan = ' . json_encode($expectedLevels));
            
            return redirect()->back()->with('validation_error', [
                'title' => 'Error Validasi',
                'message' => "Level harus berurutan (Level 1, Level 2, Level 3, dst).<br><br><strong>POTENSI BUG:</strong> Sistem adaptive learning akan bingung dengan level yang tidak berurutan.<br><strong>CATATAN:</strong> Pastikan level di data import berurutan dari 1, 2, 3, dst."
            ]);
        }

        // Update session dengan nilai total_soal_tampil yang baru
        Session::put('total_soal_tampil', $request->total_soal_tampil);

        // VALIDASI: Jumlah soal yang harus dikerjakan per level tidak boleh melebihi jumlah soal di bank soal
        $totalSoalPerLevel = 0;
        foreach ($request->jumlah_soal_per_level as $key => $value) {
            $level = str_replace('level', '', $key);
            $soalTersedia = $request->total_soal_per_level["level$level"] ?? 0;
            if ($value > $soalTersedia) {
                return redirect()->back()->with('validation_error', [
                    'title' => 'Error Validasi',
                    'message' => "Level $level: Jumlah soal yang dikerjakan ($value) tidak boleh lebih besar dari jumlah soal di bank soal ($soalTersedia)."
                ]);
            }
            $totalSoalPerLevel += (int) $value;
        }
        // VALIDASI: Total soal yang harus dikerjakan (semua level) harus sama dengan total soal tampil
        if ($totalSoalPerLevel != $request->total_soal_tampil) {
            return redirect()->back()->with('validation_error', [
                'title' => 'Error Validasi',
                'message' => "Total soal yang harus dikerjakan ($totalSoalPerLevel) tidak sama dengan total soal tampil ({$request->total_soal_tampil}).<br><br><strong>POTENSI BUG:</strong> Quiz bisa stuck atau soal tidak cukup.<br><strong>CATATAN:</strong> Sesuaikan jumlah soal per level agar totalnya sama dengan total soal tampil."
            ]);
        }

        // VALIDASI: Cegah quiz stuck jika syarat naik level tidak tercapai dan soal habis
        foreach ($request->batas_naik_level as $key => $value) {
            $level = str_replace('fase', '', $key);
            $jumlah_soal = $request->jumlah_soal_per_level["level$level"] ?? 0;
            $batas_naik = $value;
            // Jika syarat naik lebih besar dari jumlah soal, mustahil
            if ($batas_naik > $jumlah_soal) {
                return redirect()->back()->with('validation_error', [
                    'title' => 'Error Validasi',
                    'message' => "Level $level: Syarat naik level ($batas_naik) tidak boleh lebih besar dari jumlah soal yang dikerjakan ($jumlah_soal)."
                ]);
            }
            // Jika syarat naik level terlalu rendah, tetap valid, tapi cek kemungkinan stuck
            // Simulasi: Jika siswa menjawab semua soal tapi benar kurang dari syarat naik, quiz stuck
            // Contoh: syarat naik 3 dari 10, jika siswa hanya benar 2, quiz stuck
            // Validasi: syarat naik level harus bisa dicapai dengan minimal 1 benar di setiap soal
            // (tidak perlu, karena sudah dicegah oleh logika di atas)
            // Namun, jika syarat naik terlalu rendah, warning saja (tidak error)
            // Jika syarat naik terlalu tinggi, error
            // Jika syarat naik level tidak tercapai dan soal habis, quiz stuck
            // (Sudah dicegah oleh validasi di atas)
        }

        // VALIDASI: Cegah quiz stuck jika siswa gagal naik level dan soal habis
        /*
        $levelKeys = array_keys($request->jumlah_soal_per_level);
        $levelCount = count($levelKeys);
        for ($i = 0; $i < $levelCount - 1; $i++) { // Kecuali level terakhir
            $currentLevelKey = $levelKeys[$i];
            $nextLevelKeys = array_slice($levelKeys, $i + 1);
            $soalDiLevelIni = (int) $request->jumlah_soal_per_level[$currentLevelKey];
            $soalDiLevelBerikutnya = 0;
            foreach ($nextLevelKeys as $k) {
                $soalDiLevelBerikutnya += (int) $request->jumlah_soal_per_level[$k];
            }
            $totalSoalTampil = (int) $request->total_soal_tampil;
            $batasNaik = (int) ($request->batas_naik_level['fase' . ($i + 1)] ?? 0);
            // Jika semua soal di level ini habis, dan siswa gagal naik (benar < batas naik), quiz stuck
            // Kondisi: soal di level ini = total soal tampil - soal di level berikutnya
            if ($soalDiLevelIni === $totalSoalTampil - $soalDiLevelBerikutnya && $soalDiLevelIni > 0 && $batasNaik > 0) {
                return redirect()->back()->with('validation_error', [
                    'title' => 'Konfigurasi Quiz Tidak Valid',
                    'message' => "Quiz tidak bisa disimpan karena pada fase " . ($i + 1) . ", siswa yang gagal naik ke level berikutnya akan kehabisan soal. Mohon ulangi konfigurasi quiz agar lebih seimbang dan baik."
                ]);
            }
        }
        */

        try {
            // Simpan quiz dan soalnya ke DB
            $quiz = Quizzes::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'matapelajaran_id' => $request->matapelajaran_id,
                'total_soal' => $request->total_soal,
                'total_soal_tampil' => $request->total_soal_tampil,
                'waktu' => $request->waktu,
            ]);

            QuizLevelSetting::create([
                'quiz_id' => $quiz->id,
                'jumlah_soal_per_level' => json_encode($request->jumlah_soal_per_level),
                'level_awal' => session('level_awal') ?? 1,
                'batas_naik_level' => json_encode($request->batas_naik_level),
                'skor_level' => json_encode(session('skor_level')),
                'kkm' => session('kkm') ?? 75,
            ]);

            // Menampung data dalam array sebelum disimpan
            $quizQuestionsData = [];

            foreach ($preview as $row) {
                $jawabanBenar = strtolower(trim($row[2]));
                // Menyiapkan data untuk disimpan
                $quizQuestionsData[] = [
                    'quiz_id' => $quiz->id,
                    'pertanyaan' => $row[1],
                    'opsi_a' => $row[4],
                    'opsi_b' => $row[5],
                    'opsi_c' => $row[6],
                    'opsi_d' => $row[7],
                    'jawaban_benar' => $jawabanBenar,
                    'level' => $row[3],
                    'skor' => $row[8],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Simpan semua data sekali gus menggunakan insert
            if (!empty($quizQuestionsData)) {
                QuizQuestions::insert($quizQuestionsData);
            }

            // Hapus session
            $this->removeSession();

            $matpel = MataPelajaran::findOrFail($request->matapelajaran_id);
            // Cari siswa berdasarkan kelas dan tahun ajaran
            $siswas = Siswa::where('kelas', $matpel['kelas'])
                ->where('tahun_ajaran', $matpel['tahun_ajaran'])
                ->get();

            // Kirim notifikasi ke setiap siswa
            foreach ($siswas as $siswa) {
                $siswa->notify(new QuizBaruNotification($quiz));
            }

            \Log::info('Quiz berhasil diimport: ' . $request->judul);
            return redirect()->route('quiz')->with('success_message', [
                'title' => 'Berhasil',
                'message' => 'Data quiz berhasil disimpan dan notifikasi telah dikirim ke siswa.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Quiz Import Error: ' . $e->getMessage());
            return redirect()->back()->with('validation_error', [
                'title' => 'Terjadi Kesalahan',
                'message' => 'Gagal menyimpan data quiz: ' . $e->getMessage()
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Quiz Import Error: Database error - ' . $e->getMessage());
            return redirect()->back()->with('validation_error', [
                'title' => 'Terjadi Kesalahan',
                'message' => 'Gagal menyimpan data quiz. Silakan coba lagi.'
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $quiz = Quizzes::findOrFail($request->formid);
            $quiz->delete();
            return redirect()->back()->with('success_message', [
                'title' => 'Berhasil',
                'message' => 'Data quiz berhasil dihapus.'
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('validation_error', [
                'title' => 'Terjadi Kesalahan',
                'message' => 'Gagal menghapus data quiz: ' . $th->getMessage()
            ]);
        }
    }
}
