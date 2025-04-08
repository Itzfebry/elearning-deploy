<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function find($id)
    {

    }

    public function store($data)
    {
        return $this->model->create([
            "email" => $data["email"],
            "password" => $data["nip"],
            "role" => $data["role"],
        ]);
    }

    public function update($data, $id)
    {
        return $this->model->where('id', $id)->update([
            "email" => $data["email"],
        ]);
    }

    public function destroy($id)
    {
        return $this->model->where('id', $id)->delete();
    }

}