<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
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
    public function index()
    {
        return view("pages.siswa.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::get();
        return view("pages.siswa.create", compact("kelas"));
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
            return redirect()->route("guru");
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
        return view("pages.siswa.edit", compact("id"));
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
