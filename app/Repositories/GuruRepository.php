<?php

namespace App\Repositories;

use App\Models\Guru;

class GuruRepository
{
    protected $model;

    public function __construct(Guru $guru)
    {
        $this->model = $guru;
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

}