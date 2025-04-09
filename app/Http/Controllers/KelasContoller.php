<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Repositories\KelasRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KelasContoller extends Controller
{
    protected $param;
    protected $paramUser;

    public function __construct(KelasRepository $kelas, UserRepository $user)
    {
        $this->param = $kelas;
        $this->paramUser = $user;
    }

    public function index()
    {
        return view("pages.kelas.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guru = Guru::all();
        return view("pages.kelas.create", compact("guru"));
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
                'nip' => 'required|string|size:18|unique:guru,nip',
                'nama' => 'required|string',
                'jk' => 'required',
            ]);

            if (Guru::where('nip', $data['nip'])->exists()) {
                Alert::error("Terjadi Kesalahan", "NIP sudah terdaftar.");
                return back()->withInput();
            }

            $dataUser['nip'] = $request->nip;
            $user = $this->param2->store($dataUser);

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
        return view("pages.kelas.edit", compact("id"));
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
