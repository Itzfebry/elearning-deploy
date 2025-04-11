<?php

namespace App\Repositories;

use App\Models\Guru;

class SubmitTugasRepository
{
    protected $model;

    public function __construct(Guru $guru)
    {
        $this->model = $guru;
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
            "nip" => $data["nip"],
            "nama" => $data["nama"],
            "jk" => $data["jk"],
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