<?php

namespace App\Http\Controllers;

abstract class Controller
{

    public function successResponse($message, $code = 200, $data = null)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'code' => $code,
            'data' => $data
        ]);
    }

    public function errorResponse($message, $code = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'code' => $code,
        ]);
    }

    // list data paginate response
    public function paginateResponse($message, $code = 200, $data = null)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'code' => $code,
            'data' => $data->items(),
            'meta' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
            ]
        ]);
    }
}
