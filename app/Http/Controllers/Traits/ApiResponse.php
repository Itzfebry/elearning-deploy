<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function okApiResponse($data, $message = 'Success'): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    /**
     * Response error (400/500 Internal Server Error)
     */
    protected function errorApiResponse($message = 'Something went wrong', $statusCode = 500): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], $statusCode);
    }
}
