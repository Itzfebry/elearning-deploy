<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Repositories\SiswaRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SiswaController extends Controller
{
    protected $param;
    protected $paramUser;

    public function __construct(SiswaRepository $siswa, UserRepository $user)
    {
        $this->param = $siswa;
        $this->paramUser = $user;
    }
    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $search = $request->has('search') ? $request->get('search') : null;
        $siswa = $this->param->getData($search, $limit);
        return view("pages.role_admin.siswa.index", compact("siswa"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::get();
        $tahunAjaran = TahunAjaran::get();
        return view("pages.role_admin.siswa.create", compact("kelas", 'tahunAjaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $dataUser = $request->validate([
                'email' => 'required',
                'role' => 'required',
            ]);

            $data = $request->validate([
                'nisn' => 'required|string|size:10|unique:siswa,nisn',
                'nama' => 'required|string',
                'jk' => 'required',
                'kelas' => 'required',
                'tahun_ajaran' => 'required',
            ]);

            if (Siswa::where('nisn', $data['nisn'])->exists()) {
                Alert::error("Terjadi Kesalahan", "NISN sudah terdaftar.");
                return back()->withInput();
            }

            $dataUser['pass'] = $request->nisn;
            $user = $this->paramUser->store($dataUser);

            $data["user_id"] = $user->id;
            $this->param->store($data);
            Alert::success("Berhasil", "Data Berhasil di simpan.");
            return redirect()->route("siswa");
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
        $kelas = Kelas::get();
        $tahunAjaran = TahunAjaran::get();
        $siswa = Siswa::find($id);
        return view("pages.role_admin.siswa.edit", compact(["kelas", "siswa", "tahunAjaran"]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $dataUser = $request->validate([
                'email' => 'required',
            ]);

            $data = $request->validate([
                'nisn' => 'required|string|size:10',
                'nama' => 'required|string',
                'jk' => 'required',
                'kelas' => 'required',
                'tahun_ajaran' => 'required',
            ]);

            $this->paramUser->update($dataUser, $request->user_id);
            $this->param->update($data, $id);
            Alert::success("Berhasil", "Data Berhasil di ubah.");
            return redirect()->route("siswa");
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
            $this->paramUser->destroy($request->user_id);
            Alert::success("Berhasil", "Data Berhasil di Hapus.");
            return redirect()->route("siswa");
        } catch (\Exception $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back()->withInput();
        }
    }
}
