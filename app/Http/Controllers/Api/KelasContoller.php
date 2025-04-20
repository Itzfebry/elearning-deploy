<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasContoller extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $kelas = Kelas::orderBy("nama", "ASC")->get();
        return $this->okApiResponse($kelas);
    }
}
