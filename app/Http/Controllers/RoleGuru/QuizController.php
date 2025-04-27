<?php

namespace App\Http\Controllers\RoleGuru;

use App\Exports\QuizExport;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\QuizQuestions;
use App\Models\Quizzes;
use App\Models\TahunAjaran;
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
        $judulQuiz = Quizzes::with('mataPelajaran')->whereHas('mataPelajaran', function ($q) use ($nip) {
            $q->where('guru_nip', $nip);
        })->get();
        $kelas = Kelas::all();
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->get();

        $quiz = $this->param->getData($request);
        return view("pages.role_guru.quiz.index", compact(['matpel', 'judulQuiz', 'kelas', 'tahunAjaran', 'quiz']));
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

        $filteredRows = []; // baris valid

        foreach ($rows as $index => $row) {
            if ($index == 0 || !empty($row[1])) {
                // Simpan header atau soal dengan kolom Pertanyaan (index 1) tidak kosong
                $filteredRows[] = $row;
            }
        }

        $soalCount = count($filteredRows) - 1;

        // Simpan preview dan jumlah soal ke session
        Session::put('judul', $request->judul);
        Session::put('deskripsi', $request->deskripsi);
        Session::put('matapelajaran_id', $request->matapelajaran_id);
        Session::put('preview_soal', $filteredRows);
        Session::put('total_soal', $soalCount);
        Session::put('uploaded_filename', $request->file('file')->getClientOriginalName());

        return redirect()->back();
    }


    public function resetPreview()
    {
        session()->forget('judul');
        session()->forget('deskripsi');
        session()->forget('matapelajaran_id');
        session()->forget('preview_soal');
        session()->forget('total_soal');
        session()->forget('uploaded_filename');

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

        if (!$preview || count($preview) <= 1) {
            return redirect()->back()->with('error', 'Tidak ada data untuk disimpan.');
        }

        // Simpan quiz dan soalnya ke DB
        $quiz = Quizzes::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'matapelajaran_id' => $request->matapelajaran_id,
            'total_soal' => $request->total_soal,
        ]);

        foreach ($preview as $index => $row) {
            if ($index === 0)
                continue; // Skip header
            QuizQuestions::create([
                'quiz_id' => $quiz->id,
                'pertanyaan' => $row[1],
                'level' => $row[3],
                'jawaban_benar' => $row[2],
                'opsi_a' => $row[4],
                'opsi_b' => $row[5],
                'opsi_c' => $row[6],
                'opsi_d' => $row[7],
            ]);
        }

        // Hapus session
        session()->forget('judul');
        session()->forget('deskripsi');
        session()->forget('matapelajaran_id');
        session()->forget('preview_soal');
        session()->forget('total_soal');
        session()->forget('uploaded_filename');

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
    public function destroy(string $id)
    {
        //
    }
}
