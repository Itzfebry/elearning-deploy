<?php

namespace App\Http\Controllers\RoleGuru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\Tugas;
use App\Repositories\TugasRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class TugasController extends Controller
{
    protected $param;

    public function __construct(TugasRepository $tugas)
    {
        $this->param = $tugas;
    }

    public function dateFormat($date)
    {
        return date('Y-m-d', strtotime($date));
    }

    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $search = $request->has('search') ? $request->get('search') : null;
        $tugas = $this->param->getData($search, $limit);
        return view("pages.role_guru.tugas.index", compact("tugas"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mataPelajaran = MataPelajaran::where('guru_nip', Auth::user()->guru->nip)->get();
        $kelas = Kelas::all();
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->get();
        return view("pages.role_guru.tugas.create", compact(["mataPelajaran", "kelas", "tahunAjaran"]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request['tanggal'] = $this->dateFormat($request->input('tanggal'));
            $request['tenggat'] = $this->dateFormat($request->input('tenggat'));

            $data = $request->validate([
                'tanggal' => 'required',
                'tenggat' => 'required',
                'nama' => 'required',
                'matapelajaran_id' => 'required',
                'kelas' => 'required',
                'tahun_ajaran' => 'required',
            ]);

            $this->param->store($data);
            Alert::success("Berhasil", "Data Berhasil di simpan.");
            return redirect()->route("tugas");
        } catch (\Exception $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back()->withInput();
        } catch (QueryException $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back()->withInput();
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
        return view("pages.role_guru.tugas.edit");
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
