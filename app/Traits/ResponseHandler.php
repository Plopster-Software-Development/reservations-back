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
        ?string $resultCode = null,
        mixed $data = null,
    ): ApiResponseResource {
        $traceCode = TraceCodeMaker::fetchOrCreateTraceCode($service, $httpCode, $methodName, $className, $resultMessage);

        $resultMessage ??= $httpCode < 299 ? 'Successful Response.' : 'An Error Occurred.';

        $resultCode ??= $httpCode < 299 ? 'SUCCESS' : 'ERROR';

        return new ApiResponseResource((object) [
            'resultCode'    => $resultCode,
            'resultMessage' => $resultMessage,
            'traceCode'     => $traceCode['traceCode'],
            'result'        => $data,
            'httpCode'      => $httpCode
        ]);
    }
}
