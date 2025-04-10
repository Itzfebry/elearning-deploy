<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use App\Repositories\TahunAjaranRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TahunAjaranController extends Controller
{
    protected $param;

    public function __construct(TahunAjaranRepository $tahunAjaran)
    {
        $this->param = $tahunAjaran;
    }

    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $search = $request->has('search') ? $request->get('search') : null;
        $tahunAjaran = $this->param->getData($search, $limit);
        return view("pages.tahun_ajaran.index", compact("tahunAjaran"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.tahun_ajaran.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'tahun' => 'required',
            ]);

            $this->param->store($data);
            Alert::success("Berhasil", "Data Berhasil di Tambahkan.");
            return redirect()->route("tahun-ajaran");
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
    public function edit(Request $request)
    {
        $tahun = urldecode($request->tahun_ajaran);
        $tahunAjaran = TahunAjaran::where('tahun', $tahun)->firstOrFail();
        return view('pages.tahun_ajaran.edit', compact('tahunAjaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $tahun = urldecode($request->tahun_ajaran);

            $data = $request->validate([
                'status' => 'required',
            ]);

            $this->param->update($data, $tahun);
            Alert::success("Berhasil", "Data Berhasil di Ubah.");
            return redirect()->route("tahun-ajaran");
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
