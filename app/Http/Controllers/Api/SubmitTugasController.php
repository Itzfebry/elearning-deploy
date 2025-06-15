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

    public function detail(Request $request)
    {
        $data = $this->param->detail($request);
        return $this->okApiResponse($data, "Berhasil get submit tugas");
    }

    public function update(Request $request)
    {
        $data = $this->param->update($request);
        return $this->okApiResponse($data, "Update Tugas Berhasil");
    }

    public function updateNilai(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:submit_tugas,id',
            'nilai' => 'required|integer|min:0|max:100'
        ]);

        $data = $this->param->update($request);
        return $this->okApiResponse($data, "Nilai berhasil diperbarui");
    }
}
