<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Materi;
use App\Models\Siswa;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    use ApiResponse;
    public function getMatpel(Request $request)
    {
        $data = [];
        $query = [];
        $user = $request->user();
        if ($user->role == 'siswa') {
            $data = Siswa::where('user_id', $user->id);
        } else {
            $data = Guru::where('user_id', $user->id);
        }

        $data = $data->with('user')->first();

        if ($data->user->role == "siswa") {
            $query = MataPelajaran::where(function ($query) use ($data) {
                $query->where('kelas', $data->kelas)
                    ->where('tahun_ajaran', $data->tahun_ajaran);
            });
        } else {
            $query = MataPelajaran::where(function ($query) use ($data, $request) {
                $query->where('guru_nip', $data->nip)
                    ->where("kelas", $request->kelas);
            });
        }
        $query = $query->with(['guru', 'materi'])->get();

        $result = $query->map(function ($item) {
            $materi = $item->materi ?? collect();

            $jumlahBuku = $materi->where('type', 'buku')->count();
            $jumlahVideo = $materi->where('type', 'video')->count();

            return array_merge(
                $item->toArray(),
                [
                    'jumlah_buku' => $jumlahBuku,
                    'jumlah_video' => $jumlahVideo,
                ]
            );
        });

        return $this->okApiResponse($result);

    }

    public function getMatpelSimple(Request $request)
    {
        $data = [];
        $query = [];
        $user = $request->user();
        if ($user->role == 'siswa') {
            $data = Siswa::where('user_id', $user->id);
        } else {
            $data = Guru::where('user_id', $user->id);
        }

        $data = $data->with('user')->first();

        if ($data->user->role == "siswa") {
            $query = MataPelajaran::where(function ($query) use ($data) {
                $query->where('kelas', $data->kelas)
                    ->where('tahun_ajaran', $data->tahun_ajaran);
            });
        } else {
            $query = MataPelajaran::where(function ($query) use ($data) {
                $query->where('guru_nip', $data->nip);
            });
        }
        $query = $query->with('guru')->get();

        return $this->okApiResponse($query);

    }
}
