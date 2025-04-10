<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\MataPelajaran;

class MataPelajaranRepository
{
    protected $model;

    public function __construct(MataPelajaran $mataPelajaran)
    {
        $this->model = $mataPelajaran;
    }

    public function find($id)
    {
        return $this->model->with(["guru", "kelas", "tahunAjaran"])->find($id);
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
            ->orWhereHas("guru", function ($query) use ($search) {
                $query->where("nama", "like", "%" . $search . "%");
            })
            ->paginate($limit);

        return $query;
    }

    public function store($data)
    {
        return $this->model->create([
            "nama" => $data["nama"],
            "guru_nip" => $data["guru_nip"],
            "kelas" => $data["kelas"],
            "tahun_ajaran" => $data["tahun_ajaran"],
        ]);
    }

    public function update($data, $id)
    {
        return $this->model->where('id', $id)->update([
            "nama" => $data["nama"],
            "guru_nip" => $data["guru_nip"],
            "kelas" => $data["kelas"],
            "tahun_ajaran" => $data["tahun_ajaran"],
        ]);
    }

    public function destroy($id)
    {
        // return $this->model->where('id', $id)->delete();
    }

}