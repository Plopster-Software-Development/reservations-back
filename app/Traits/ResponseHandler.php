<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseHandler
{
    /**
     * Succesfull base response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponse(
        string $traceCode,
        string $resultCode = 'SUCCESS',
        string $resultMessage = 'Successful Response',
        mixed $data = null,
        int $httpCode = 200
    ): JsonResponse {
        $finalResponse = [
            'resultCode' => $resultCode,
            'resultMessage' => $resultMessage,
            'traceCode' => $traceCode,
            'result' => $data,
        ];

        $response = response()->json($finalResponse, $httpCode);
        $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        return $response;
    }

    /**
     * Error base response
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function errorResponse(
        string $traceCode,
        string $resultCode = 'ERROR',
        string $message = 'An error occurred while processing the request.',
        int $httpCode = 500,
        mixed $result = []
    ): JsonResponse {
        $finalResponse = [
            'resultCode' => $resultCode,
            'resultMessage' => $message,
            'traceCode' => $traceCode,
        ];

        if (isset($result)) {
            $finalResponse['result'] = $result;
        }

        $response = response()->json($finalResponse, $httpCode);
        $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        return $response;
    }
}
