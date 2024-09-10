<?php

namespace App\Traits;

use App\Facades\TraceCodeMaker;
use App\Http\Resources\ApiResponseResource;

trait ResponseHandler
{
    /**
     * Succesfull base response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return ApiResponseResource 
     */
    protected function response(
        int $httpCode,
        string $methodName,
        string $className,
        ?string $service = 'API',
        ?string $resultMessage = null,
        ?string $codeDescription = null,
        ?string $resultCode = null,
        mixed $data = null,
    ): ApiResponseResource {
        $traceCode = TraceCodeMaker::fetchOrCreateTraceCode($service, $httpCode, $methodName, $className, $codeDescription);

        $resultCode ??= $httpCode < 299 ? 'SUCCESS' : 'ERROR';

        return new ApiResponseResource((object) [
            'resultCode'    => $resultCode,
            'resultMessage' => $resultMessage,
            'traceCode'     => $traceCode['traceCode'],
            'result'        => $data,
            'httpCode'      => $httpCode
        ]);
        // ()->response()->setStatusCode($httpCode)
        //     ->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    }
}
