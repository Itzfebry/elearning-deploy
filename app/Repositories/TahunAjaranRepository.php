<?php

namespace App\Repositories;

use App\Models\TahunAjaran;

class TahunAjaranRepository
{
    protected $model;

    public function __construct(TahunAjaran $tahunAjaran)
    {
        $this->model = $tahunAjaran;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function getData($search, $limit = 10)
    {
        $search = strtolower($search);
        $query = $this->model
            ->where(function ($query) use ($search) {
                $query->where("tahun", "like", "%" . $search . "%")
                    ->orWhere("status", "like", "%" . $search . "%");
            })
            ->paginate($limit);

        return $query;
    }

    public function store($data)
    {
        return $this->model->create([
            "tahun" => $data["tahun"],
            "status" => "aktif",
        ]);
    }

    public function update($data, $tahun)
    {
        return $this->model->where('tahun', $tahun)->update([
            "status" => $data["status"],
        ]);
    }

    public function destroy($tahun)
    {
        return $this->model->where('tahun', $tahun)->delete();
    }

}