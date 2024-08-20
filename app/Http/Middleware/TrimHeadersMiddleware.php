<?php

namespace App\Http\Middleware;

use App\Traits\ResponseHandler;
use App\Traits\Utils;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrimHeadersMiddleware
{
    use ResponseHandler, Utils;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $sanitizedHeaders = $this::trimParams($request->headers->all(), [ '"', 'Bearer', 'Basic' ]);

            foreach ($sanitizedHeaders as $key => $values) {
                $request->headers->set($key, $values);
            }

            return $next($request);
        } catch (\Throwable $th) {
            return $this->errorResponse(__METHOD__, self::class, null, 'An unexpected error just happened, check the trace of the error.');
        }
    }
}
