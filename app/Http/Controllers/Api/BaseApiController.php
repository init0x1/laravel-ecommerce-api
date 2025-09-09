<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseApiController extends Controller
{
    /**
     * Return a JSON response for API requests
     *
     * @param mixed $data
     * @param string $message
     * @param bool $status
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse($data = null, string $message = '', bool $status = true, int $statusCode = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}
