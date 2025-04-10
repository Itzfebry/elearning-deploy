<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Repositories\WaliKelasRepository;
use Illuminate\Http\Request;

class WaliKelasController extends Controller
{
    protected $param;

    public function __construct(WaliKelasRepository $waliKelas)
    {
        $this->param = $waliKelas;
    }

    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 5;
        $search = $request->has('search') ? $request->get('search') : null;
        $waliKelas = $this->param->getData($search, $limit);
        return view('pages.wali_kelas.index', compact('waliKelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        return view('pages.wali_kelas.edit', compact(['waliKelas', 'kelas', 'guru']));
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
