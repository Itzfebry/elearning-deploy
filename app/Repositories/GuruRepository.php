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

    public function find($id)
    {

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