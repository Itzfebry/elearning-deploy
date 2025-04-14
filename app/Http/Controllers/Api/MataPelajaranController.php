<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Guru;
use App\Models\MataPelajaran;
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
            $query = MataPelajaran::where(function ($query) use ($data) {
                $query->where('guru_nip', $data->guru_nip);
            });
        }
        $query = $query->with('guru')->get();

        return $this->okApiResponse($query);
    }
}
