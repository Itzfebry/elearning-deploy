<?php

namespace App\Repositories;

use App\Models\Tugas;
use Illuminate\Support\Facades\Auth;

class TugasRepository
{
    protected $model;
    protected $nipUser;

    public function __construct(Tugas $tugas)
    {
        $this->model = $tugas;
        $this->nipUser = Auth::user()->guru->nip;
    }

    public function find($id)
    {
        return $this->model->with('mataPelajaran')->find($id);
    }

    public function getData($search, $limit = 10)
    {
        $search = strtolower($search);
        $query = $this->model
            ->where(function ($query) use ($search) {
                $query->where("nama", "like", "%" . $search . "%")
                    ->orWhere("kelas", "like", "%" . $search . "%")
                    ->orWhere("tahun_ajaran", "like", "%" . $search . "%");
            })
            ->orWhereHas("mataPelajaran", function ($query) use ($search) {
                $query->where("nama", "like", "%" . $search . "%");
            })
            ->where('guru_nip', Auth::user()->guru->nip)
            ->paginate($limit);

        return $query;
    }

    public function store($data)
    {
        return $this->model->create([
            "tanggal" => $data["tanggal"],
            "tenggat" => $data["tenggat"],
            "guru_nip" => $this->nipUser,
            "nama" => $data["nama"],
            "matapelajaran_id" => $data["matapelajaran_id"],
            "kelas" => $data["kelas"],
            "tahun_ajaran" => $data["tahun_ajaran"],
        ]);
    }

    public function update($data, $id)
    {
        return $this->model->where('id', $id)->update([
            "tanggal" => $data["tanggal"],
            "tenggat" => $data["tenggat"],
            "guru_nip" => $this->nipUser,
            "nama" => $data["nama"],
            "matapelajaran_id" => $data["matapelajaran_id"],
            "kelas" => $data["kelas"],
        ]);
    }

    public function destroy($id)
    {
        return $this->model->where('id', $id)->delete();
    }

}