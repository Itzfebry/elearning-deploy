<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Repositories\WaliKelasRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class WaliKelasController extends Controller
{
    protected $param;

    public function __construct(WaliKelasRepository $waliKelas)
    {
        $this->param = $waliKelas;
    }

    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $search = $request->has('search') ? $request->get('search') : null;
        $waliKelas = $this->param->getData($search, $limit);
        return view('pages.role_admin.wali_kelas.index', compact('waliKelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::get();
        $guru = Guru::get();
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->get();
        return view('pages.role_admin.wali_kelas.create', compact(['kelas', 'guru', 'tahunAjaran']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'kelas' => 'required',
                'tahun_ajaran' => 'required',
                'wali_nip' => 'required',
            ]);

            $this->param->store($data);
            Alert::success("Berhasil", "Data Berhasil di Tambahkan.");
            return redirect()->route("wali-kelas");
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
        $waliKelas = $this->param->find($id);
        $kelas = Kelas::get();
        $guru = Guru::get();
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->get();
        return view('pages.role_admin.wali_kelas.edit', compact(['waliKelas', 'kelas', 'guru', 'tahunAjaran']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                'kelas' => 'required',
                'tahun_ajaran' => 'required',
                'wali_nip' => 'required',
            ]);

            $this->param->update($data, $id);
            Alert::success("Berhasil", "Data Berhasil di ubah.");
            return redirect()->route("wali-kelas");
        } catch (\Exception $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back()->withInput();
        } catch (QueryException $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $this->param->destroy($request->formid);
            Alert::success("Berhasil", "Data Berhasil di hapus.");
            return redirect()->route("wali-kelas");
        } catch (\Exception $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back();
        }
    }
}
