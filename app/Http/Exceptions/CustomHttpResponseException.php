<?php

namespace Illuminate\Http\Exceptions;

use App\Http\Resources\ApiResponseResource;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CustomHttpResponseException extends HttpResponseException
{
    /**
     * The underlying response instance.
     *
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;

    /**
     * Create a new HTTP response exception instance.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @param  \Throwable  $previous
     * @return void
     */
    public function __construct(ApiResponseResource $response, ?Throwable $previous = null)
    {
        parent::__construct($previous?->getMessage() ?? '', $previous?->getCode() ?? 0, $previous);

        $this->response = $response;
    }

}
