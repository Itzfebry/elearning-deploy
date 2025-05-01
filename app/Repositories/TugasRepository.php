<?php

namespace App\Repositories;

use App\Models\Siswa;
use App\Models\Tugas;
use App\Notifications\TugasBaruNotification;
use GuzzleHttp\Psr7\Request;
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

    public function getDataApi($request)
    {
        $query = $this->model->with('mataPelajaran');

        if ($request->user->role == "siswa") {
            $query->where(function ($query) use ($request) {
                $query->where("kelas", $request->kelas)
                    ->where("tahun_ajaran", $request->tahun_ajaran);
            });
        } else {
            $query->where(function ($query) use ($request) {
                $query->where("guru_nip", $request->nip)
                    ->orWhere('kelas', $request->kelas)
                    ->orWhere('kelas', $request->tahun_ajaran);
            });
        }
        $query = $query->get();

        return $query;
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
        $tugas = $this->model->create([
            "tanggal" => $data["tanggal"],
            "tenggat" => $data["tenggat"],
            "guru_nip" => $this->nipUser,
            "nama" => $data["nama"],
            "matapelajaran_id" => $data["matapelajaran_id"],
            "kelas" => $data["kelas"],
            "tahun_ajaran" => $data["tahun_ajaran"],
        ]);

        // Cari siswa berdasarkan kelas dan tahun ajaran
        $siswas = Siswa::where('kelas', $data['kelas'])
            ->where('tahun_ajaran', $data['tahun_ajaran'])
            ->get();

        // Kirim notifikasi ke setiap siswa
        foreach ($siswas as $siswa) {
            $siswa->notify(new TugasBaruNotification($tugas));
        }

        return $tugas;
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