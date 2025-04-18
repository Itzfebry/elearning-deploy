<?php

namespace App\Repositories;

use App\Models\Tugas;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;

class ApiTugasRepository
{
    protected $model;

    public function __construct(Tugas $tugas)
    {
        $this->model = $tugas;
    }

    public function getDataApi($request, $idMatpel)
    {
        $query = $this->model->with('mataPelajaran');

        if ($request->user->role == "siswa") {
            $query->where(function ($query) use ($request) {
                $query->where("kelas", $request->kelas)
                    ->where("tahun_ajaran", $request->tahun_ajaran);
            });
        } else {
            $query->where(function ($query) use ($request) {
                $query->where("guru_nip", $request->nip)
                    ->orWhere('kelas', $request->kelas)
                    ->orWhere('kelas', $request->tahun_ajaran);
            });
        }
        $query = $query->where("matapelajaran_id", $idMatpel)->get();

        return $query;
    }
}