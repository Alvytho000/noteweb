<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse($data, $message = null, $code = 200): JsonResponse
    {
        $response = [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ];

        // Jika data adalah Resource Collection yang memiliki paginasi
        if ($data instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection && 
            $data->resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $paginated = $data->resource->toArray();
            
            $response['data'] = $data->collection; // Mengambil item saja
            $response['pagination'] = [
                'current_page' => $paginated['current_page'],
                'last_page' => $paginated['last_page'],
                'per_page' => $paginated['per_page'],
                'total' => $paginated['total'],
                'next_page_url' => $paginated['next_page_url'],
                'prev_page_url' => $paginated['prev_page_url'],
            ];
        }

        return response()->json($response, $code);
    }

    protected function errorResponse($message, $code, $data = null): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
