<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class SubmitTugasResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tanggal' => $this->tanggal ? Carbon::parse($this->tanggal)->format('Y-m-d H:i:s') : null,
            'nisn' => $this->nisn,
            'tugas_id' => $this->tugas_id,
            'text' => $this->text,
            'file' => $this->file,
            'nilai' => $this->nilai,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'siswa' => $this->whenLoaded('siswa', function () {
                return [
                    'nisn' => $this->siswa->nisn,
                    'nama' => $this->siswa->nama,
                ];
            }),
        ];
    }
} 