<?php

namespace App\Repositories;

use App\Models\Materi;

class MateriRepository
{
    protected $model;

    public function __construct(Materi $materi)
    {
        $this->model = $materi;
    }

    public function find($id)
    {
        return $this->model->with('mataPelajaran')->find($id);
    }

    public function getDataApi($matapelajaranId, $semester = 1, $type = "buku", $request)
    {
        $query = $this->model
            ->where(function ($query) use ($matapelajaranId, $semester, $type) {
                $query->where("matapelajaran_id", $matapelajaranId)
                    ->where("semester", $semester)
                    ->where("type", $type);
            })
            ->with("mataPelajaran");

        if ($request->user->role == "siswa") {
            $query->where(function ($query) use ($request) {
                $query->where("tahun_ajaran", $request->tahun_ajaran);
            });
        } else {
            $query->whereHas("mataPelajaran", function ($query) use ($request) {
                $query->where("guru_nip", $request->nip);
            });
        }

        $query = $query->get();

        return $query;
    }

    public function getData($search, $limit = 10)
    {
        $search = strtolower($search);
        $query = $this->model
            ->where(function ($query) use ($search) {
                $query->where("semester", "like", "%" . $search . "%")
                    ->orWhere("type", "like", "%" . $search . "%")
                    ->orWhere("judul_materi", "like", "%" . $search . "%")
                    ->orWhere("tahun_ajaran", "like", "%" . $search . "%");
            })
            ->orWhereHas("mataPelajaran", function ($query) use ($search) {
                $query->where("nama", "like", "%" . $search . "%");
            })
            ->paginate($limit);

        return $query;
    }

    public function store($data)
    {
        return $this->model->create([
            "tanggal" => $data["tanggal"],
            "matapelajaran_id" => $data["matapelajaran_id"],
            "semester" => $data["semester"],
            "type" => $data["type"],
            "judul_materi" => $data["judul_materi"],
            "deskripsi" => $data["deskripsi"],
            "path" => $data["path"],
            "tahun_ajaran" => $data["tahun_ajaran"],
        ]);
    }

    public function update($data, $id)
    {
        return $this->model->where('id', $id)->update([
            "matapelajaran_id" => $data["matapelajaran_id"],
            "semester" => $data["semester"],
            "type" => $data["type"],
            "judul_materi" => $data["judul_materi"],
            "deskripsi" => $data["deskripsi"],
            "path" => $data["path"],
            "tahun_ajaran" => $data["tahun_ajaran"],
        ]);
    }

    public function destroy($id)
    {
        return $this->model->where('id', $id)->delete();
    }

}