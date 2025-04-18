<?php

namespace App\Repositories;

use App\Models\SubmitTugas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApiSubmitTugasRepository
{
    protected $model;

    public function __construct(SubmitTugas $submitTugas)
    {
        $this->model = $submitTugas;
    }

    public function store($request)
    {
        $request->validate([
            'tugas_id' => 'required|exists:tugas,id',
            'nisn' => 'required|string|max:12',
            'text' => 'nullable|required_without:file|string',
            'file' => 'nullable|required_without:text|file|mimes:pdf,jpg,png',
            'tanggal' => 'required|date',
        ]);

        $filePath = null;

        if ($request->hasFile('file')) {
            $filename = Str::uuid() . '.' . $request->file->extension();
            $filePath = $request->file->storeAs('uploads/submit_tugas', $filename, 'public');
        }

        $submit = SubmitTugas::create([
            'tugas_id' => $request->tugas_id,
            'nisn' => $request->nisn,
            'tanggal' => $request->tanggal,
            'text' => $request->text,
            'file' => $filePath,
        ]);

        return $submit;
    }
}