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
        $tanggal = now()->format('Y-m-d');
        $request->validate([
            'tugas_id' => 'required|exists:tugas,id',
            'nisn' => 'required|string|max:12',
            'text' => 'nullable|required_without:file|string',
            'file' => 'nullable|required_without:text|file|mimes:pdf,jpg,png',
        ]);

        $filePath = null;

        if ($request->hasFile('file')) {
            $filename = Str::uuid() . '.' . $request->file->extension();
            $filePath = $request->file->storeAs('tugas/submit_tugas', $filename, 'public');
        }

        $submit = SubmitTugas::create([
            'tugas_id' => $request->tugas_id,
            'nisn' => $request->nisn,
            'tanggal' => $tanggal,
            'text' => $request->text,
            'file' => $filePath,
        ]);

        return $submit;
    }

    public function detail($request)
    {
        $query = $this->model->where(function ($q) use ($request) {
            $q->where('nisn', $request->nisn)
                ->where('tugas_id', $request->tugas_id);
        })->first();

        return $query;
    }

    public function update($request)
    {
        $tanggal = now()->format('Y-m-d');

        $request->validate([
            'id' => 'required|exists:submit_tugas,id',
            'nisn' => 'required|string|max:12',
            'text' => 'nullable|string',
        ]);

        $submit = $this->model->findOrFail($request->id);

        $filePath = $submit->file;

        if ($request->hasFile('file')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $filename = Str::uuid() . '.' . $request->file->extension();
            $filePath = $request->file->storeAs('tugas/submit_tugas', $filename, 'public');
        }

        $submit->update([
            'nisn' => $request->nisn,
            'tanggal' => $tanggal,
            'text' => $request->text,
            'file' => $filePath,
        ]);

        return $submit;
    }


}