<?php

namespace App\Http\Controllers;

use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $param;

    public function __construct(DashboardRepository $dashboard)
    {
        $this->param = $dashboard;
    }

    public function index(Request $request)
    {
        $tahunAjaran = $request->get('tahun_ajaran');
        $kelas = $request->get('kelas');
        $dashboard = $this->param->getData($tahunAjaran, $kelas);
        return view('pages.role_admin.admin_dashboard.index', compact('dashboard'));
    }

    public function indexAdmin()
    {
        $dashboard = $this->param->getData();
        return view("pages.role_guru.dashboard.index", compact("dashboard"));
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
        //
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
