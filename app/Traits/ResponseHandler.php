<?php

namespace App\Traits;

use App\Facades\TraceCodeMaker;
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
        ?string $methodName,
        ?string $className,
        ?string $service = null,
        ?string $resultMessage = null,
        ?string $codeDescription = null,
        ?string $resultCode = null,
        int $httpCode = 200,
        mixed $data = null,
    ): JsonResponse {

        $traceCode = TraceCodeMaker::fetchOrCreateTraceCode($service ?? 'API', $httpCode, $methodName, $className, $codeDescription);

        $finalResponse = [
            'resultCode'    => $resultCode ?? 'SUCCESS',
            'resultMessage' => $resultMessage ?? 'Successful Response',
            'traceCode'     => $traceCode['traceCode'] ?? 'Could not get a trace code.',
            'result'        => $data,
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
        ?string $methodName,
        ?string $className,
        ?string $service = null,
        ?string $message = null,
        ?string $errorDescription = null,
        ?string $resultCode = null,
        int $httpCode = 500,
        mixed $result = [],
    ): JsonResponse {

        $traceCode = TraceCodeMaker::fetchOrCreateTraceCode($service ?? 'API', $httpCode, $methodName, $className, $errorDescription);

        $finalResponse = [
            'resultCode'    => $resultCode ?? 'ERROR',
            'resultMessage' => $message ?? 'An error occurred while processing the request.',
            'traceCode'     => $traceCode['traceCode'] ?? 'Could not get a trace code.',
        ];

        if (isset($result) && !empty($result)) {
            $finalResponse['result'] = $result;
        }

        $response = response()->json($finalResponse, $httpCode);
        $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        return $response;
    }
}
