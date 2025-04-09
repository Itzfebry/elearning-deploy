<?php

namespace App\Repositories;

use App\Models\Siswa;

class SiswaRepository
{
    protected $model;

    public function __construct(Siswa $siswa)
    {
        $this->model = $siswa;
    }

    public function find($id)
    {
        return $this->model->with('user')->find($id);
    }

    public function getData($search, $limit = 10)
    {
        $search = strtolower($search);
        $query = $this->model
            ->where(function ($query) use ($search) {
                $query->where("nama", "like", "%" . $search . "%");
            })
            ->orWhereHas("user", function ($query) use ($search) {
                $query->where("email", "like", "%" . $search . "%");
            })
            ->paginate($limit);

        return $query;
    }

    public function store($data)
    {
        return $this->model->create([
            "user_id" => $data["user_id"],
            "nisn" => $data["nisn"],
            "nama" => $data["nama"],
            "jk" => $data["jk"],
            "kelas" => $data["kelas"],
        ]);
    }

    public function update($data, $id)
    {
        return $this->model->where('id', $id)->update([
            "nip" => $data["nip"],
            "nama" => $data["nama"],
            "jk" => $data["jk"],
        ]);
    }

    public function destroy($id)
    {
        return $this->model->where('id', $id)->delete();
    }

}
