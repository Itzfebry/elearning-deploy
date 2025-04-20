<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    use ApiResponse;

    public function getTahunAjaran(Request $request)
    {
        $data = TahunAjaran::where("status", "aktif")->get();
        return $this->okApiResponse($data);
    }
}
