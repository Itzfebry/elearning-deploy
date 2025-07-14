<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TugasResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'tanggal' => $this->tanggal ? Carbon::parse($this->tanggal)->format('Y-m-d H:i:s') : null,
            'tenggat' => $this->tenggat ? Carbon::parse($this->tenggat)->format('Y-m-d H:i:s') : null,
            'guru_nip' => $this->guru_nip,
            'matapelajaran_id' => $this->matapelajaran_id,
            'kelas' => $this->kelas,
            'tahun_ajaran' => $this->tahun_ajaran,
            'mata_pelajaran' => $this->whenLoaded('mataPelajaran', function () {
                return [
                    'id' => $this->mataPelajaran->id,
                    'nama' => $this->mataPelajaran->nama,
                ];
            }),
            'submit_tugas' => SubmitTugasResource::collection($this->whenLoaded('submitTugas')),
        ];
    }
} 