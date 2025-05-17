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
        $judulQuiz = Quizzes::where('judul', $request->judul)->first();
        $kelas = Kelas::all();
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->get();

        $quiz = $this->param->getData($request);
        return view("pages.role_guru.quiz.index", compact(['matpel', 'judulQuiz', 'kelas', 'tahunAjaran', 'quiz']));
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
        $batasNaikLevel = [];

        foreach ($rows as $index => $row) {
            if ($index == 0 || !empty($row[1])) {
                $filteredRows[] = $row;

                $level = $row[3];
                if (!empty($level) && $index != 0) {
                    $key = 'level' . $level;
                    if (!isset($jumlahSoalPerLevel[$key])) {
                        $jumlahSoalPerLevel[$key] = 0;
                    }
                    $jumlahSoalPerLevel[$key]++;
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
        }


        $soalCount = count($filteredRows) - 1;

        // Simpan preview dan jumlah soal ke session
        Session::put('judul', $request->judul);
        Session::put('deskripsi', $request->deskripsi);
        Session::put('matapelajaran_id', $request->matapelajaran_id);
        Session::put('preview_soal', $filteredRows);
        Session::put('total_soal', $soalCount);
        Session::put('total_soal_tampil', $request->total_soal_tampil);
        Session::put('uploaded_filename', $request->file('file')->getClientOriginalName());

        // quiz level settings
        Session::put('jumlah_soal_per_level', $jumlahSoalPerLevel);
        Session::put('level_awal', $request->level_awal);
        Session::put('batas_naik_level', $batasNaikLevel);
        Session::put('kkm', $request->kkm);

        return redirect()->back();
    }

    protected function removeSession()
    {
        session()->forget('judul');
        session()->forget('deskripsi');
        session()->forget('matapelajaran_id');
        session()->forget('preview_soal');
        session()->forget('total_soal');
        session()->forget('total_soal_tampil');
        session()->forget('uploaded_filename');

        // quiz level settings
        session()->forget('jumlah_soal_per_level');
        session()->forget('level_awal');
        session()->forget('batas_naik_level');
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
        return view("pages.role_guru.quiz.create", compact(['matpel']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $preview = session('preview_soal');
        array_shift($preview);

        if (!$preview || count($preview) <= 1) {
            Alert::error("Terjadi Kesalahan", "Tidak ada data untuk disimpan.");
            return redirect()->back();
        }

        // Simpan quiz dan soalnya ke DB
        $quiz = Quizzes::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'matapelajaran_id' => $request->matapelajaran_id,
            'total_soal' => $request->total_soal,
            'total_soal_tampil' => $request->total_soal_tampil,
        ]);

        QuizLevelSetting::create([
            'quiz_id' => $quiz->id,
            'jumlah_soal_per_level' => json_encode(session('jumlah_soal_per_level')),
            'level_awal' => session('level_awal') ?? 1,
            'batas_naik_level' => json_encode(session('batas_naik_level')),
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

        Alert::success("Berhasil", "Data Berhasil di simpan.");
        return redirect()->route('quiz');
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
            Alert::success("Berhasil", "Data berhasil dihapus.");
            return redirect()->back();
        } catch (\Throwable $th) {
            Alert::error($th->getMessage());
        }
    }
}
