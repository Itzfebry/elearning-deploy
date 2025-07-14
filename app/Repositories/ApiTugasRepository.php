<?php

namespace App\Repositories;

use App\Models\Siswa;
use App\Models\SubmitTugas;
use App\Models\Tugas;
use App\Http\Resources\TugasResource;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;

class ApiTugasRepository
{
    protected $model;

    public function __construct(Tugas $tugas)
    {
        $this->model = $tugas;
    }

    public function getDataApi($request, $param)
    {
        $query = $this->model->with(['mataPelajaran', 'submitTugas']);

        if ($request->user->role == "siswa") {
            if ($param->type_tugas == "selesai") {
                $query->withWhereHas("submitTugas", function ($q) use ($request) {
                    $q->where('nisn', $request->nisn);
                });
            } else {
                $query->whereDoesntHave("submitTugas", function ($q) use ($request) {
                        $q->where('nisn', $request->nisn);
                    });
            }

            $query->where(function ($q) use ($request) {
                $q->where("kelas", $request->kelas)
                    ->where("tahun_ajaran", $request->tahun_ajaran);
            });
        } else {
            $query->where(function ($q) use ($request, $param) {
                $q->where("guru_nip", $request->nip)
                    ->where('kelas', $param->kelas)
                    ->where('tahun_ajaran', $param->tahun_ajaran);
            });
        }

        $query->where('matapelajaran_id', $param->id_matpel);

        $data = $query->get();

        return TugasResource::collection($data);
    }

    public function getSubmitTugasSiswa($request)
    {
        $query = Siswa::where(function ($q) use ($request) {
            $q->where("kelas", $request->kelas)
                ->where("tahun_ajaran", $request->tahun_ajaran);
        });

        if ($request->type_tugas == "selesai") {
            $query->withWhereHas("submitTugas", function ($q) use ($request) {
                $q->where("tugas_id", $request->tugas_id);
            })->with([
                        'submitTugas' => function ($q) use ($request) {
                            $q->where("tugas_id", $request->tugas_id)
                                ->with('tugas');
                        }
                    ]);
        } else {
            $query->with('submitTugas')
                ->whereDoesntHave("submitTugas", function ($q) use ($request) {
                    $q->where("tugas_id", $request->tugas_id);
                });
        }
        $data = $query->get();

        return $data;
    }
}