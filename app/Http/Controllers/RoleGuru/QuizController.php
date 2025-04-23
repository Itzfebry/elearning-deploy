<?php

namespace App\Http\Controllers\RoleGuru;

use App\Exports\QuizExport;
use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.role_guru.quiz.index");
    }

    public function excelDownload()
    {
        return Excel::download(new QuizExport, "format_excel_untuk_quiz.xlsx");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nip = Auth::user()->guru->nip;
        $matpel = MataPelajaran::where("guru_nip", $nip)->get();
        return view("pages.role_guru.quiz.create", compact(['matpel']));
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
