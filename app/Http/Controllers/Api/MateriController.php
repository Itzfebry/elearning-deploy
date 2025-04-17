<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Guru;
use App\Models\Siswa;
use App\Repositories\MateriRepository;
use Exception;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    protected $param;
    use ApiResponse;

    public function __construct(MateriRepository $materi)
    {
        $this->param = $materi;
    }

    public function getMateri(Request $request)
    {
        try {
            $data = [];
            $user = $request->user();
            if ($user->role == 'siswa') {
                $data = Siswa::where('user_id', $user->id);
            } else {
                $data = Guru::where('user_id', $user->id);
            }

            $data = $data->with('user')->first();

            $result = $this->param->getDataApi($request->id_matpel, $request->semester, $request->type, $data);
            return $this->okApiResponse($result);
        } catch (Exception $e) {
            return $this->errorApiResponse("Terjadi Kesalahan " . $e->getMessage());
        }
    }
}
