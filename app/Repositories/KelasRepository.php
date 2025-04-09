<?php

namespace App\Repositories;

use App\Models\Kelas;

class KelasRepository
{
    protected $model;

    public function __construct(Kelas $kelas)
    {
        $this->model = $kelas;
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
            "nama" => $data["nama"],
            "nip_wali" => $data["nip_wali"],
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