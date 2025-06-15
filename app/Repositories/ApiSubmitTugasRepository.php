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
            'nilai' => 'nullable|integer|min:0|max:100'
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
            'nilai' => $request->nilai
        ]);

        return $submit;
    }

    public function detail($request)
    {
        // Cek jika ada parameter submit_id, maka ambil berdasarkan id
        if ($request->has('submit_id')) {
            $query = $this->model->with('siswa')->find($request->submit_id);
            return $query;
        }
        
        // Default: cari berdasarkan nisn dan tugas_id
        $query = $this->model->with('siswa')->where(function ($q) use ($request) {
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
            'nilai' => 'nullable|integer|min:0|max:100'
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
            'nilai' => $request->nilai
        ]);

        return $submit;
    }
}