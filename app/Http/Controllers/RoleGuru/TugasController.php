<?php

namespace App\Http\Controllers\RoleGuru;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Repositories\TugasRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    protected $param;

    public function __construct(TugasRepository $tugas)
    {
        $this->param = $tugas;
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
        return view("pages.role_guru.tugas.create");
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
