<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Repositories\ApiSubmitTugasRepository;
use Illuminate\Http\Request;

class SubmitTugasController extends Controller
{
    protected $param;
    use ApiResponse;

    public function __construct(ApiSubmitTugasRepository $submitTugas)
    {
        $this->param = $submitTugas;
    }

    public function store(Request $request)
    {
        $data = $this->param->store($request);
        return $this->okApiResponse($data, "Submit Tugas Berhasil");
    }
}
