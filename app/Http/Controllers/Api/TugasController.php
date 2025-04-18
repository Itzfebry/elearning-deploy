<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Guru;
use App\Models\Siswa;
use App\Repositories\ApiTugasRepository;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    protected $param;
    use ApiResponse;

    public function __construct(ApiTugasRepository $tugas)
    {
        $this->param = $tugas;
    }

    public function getTugas(Request $request)
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

            $result = $this->param->getDataApi($data, $request->id_matpel);
            return $this->okApiResponse($result);
        } catch (\Exception $e) {
            return $this->errorApiResponse("Error : " . $e->getMessage());
        }
    }
}
