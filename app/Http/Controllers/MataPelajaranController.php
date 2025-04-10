<?php

namespace App\Http\Controllers;

use App\Repositories\MataPelajaranRepository;
use Illuminate\Http\Request;

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
        return view("pages.mata_pelajaran.create");
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
