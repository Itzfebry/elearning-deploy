<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Repositories\MataPelajaranRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MataPelajaranController extends Controller
{
    protected $param;

    public function __construct(MataPelajaranRepository $mataPelajaran)
    {
        $this->param = $mataPelajaran;
    }
    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $search = $request->has('search') ? $request->get('search') : null;
        $mataPelajaran = $this->param->getData($search, $limit);
        return view("pages.mata_pelajaran.index", compact("mataPelajaran"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guru = Guru::all();
        $kelas = Kelas::all();
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->get();
        return view("pages.mata_pelajaran.create", compact("guru", "kelas", "tahunAjaran"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nama' => 'required',
                'guru_nip' => 'required',
                'kelas' => 'required',
                'tahun_ajaran' => 'required',
            ]);

            $this->param->store($data);
            Alert::success("Berhasil", "Data Berhasil di Tambahkan.");
            return redirect()->route("mata-pelajaran");
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
        $mataPelajaran = $this->param->find($id);
        return view("pages.mata_pelajaran.edit", compact("mataPelajaran"));
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
