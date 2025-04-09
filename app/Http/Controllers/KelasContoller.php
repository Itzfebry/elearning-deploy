<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Repositories\KelasRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KelasContoller extends Controller
{
    protected $param;
    protected $paramUser;

    public function __construct(KelasRepository $kelas)
    {
        $this->param = $kelas;
    }

    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 5;
        $search = $request->has('search') ? $request->get('search') : null;
        $kelas = $this->param->getData($search, $limit);
        return view("pages.kelas.index", compact("kelas"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.kelas.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'nama' => 'required|string',
            ]);

            if (Kelas::where('nama', $data['nama'])->exists()) {
                Alert::error("Terjadi Kesalahan", "Kelas sudah terdaftar.");
                return back()->withInput();
            }

            $this->param->store($data);
            Alert::success("Berhasil", "Data Berhasil di simpan.");
            return redirect()->route("kelas");
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
        $kelas = Kelas::find($id);
        return view("pages.kelas.edit", compact("kelas"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $data = $request->validate([
                'nama' => 'required|string',
            ]);

            $this->param->update($data, $id);
            Alert::success("Berhasil", "Data Berhasil di ubah.");
            return redirect()->route("kelas");
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
    public function destroy(string $id)
    {
        //
    }
}
