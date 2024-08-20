<?php

namespace App\Http\Middleware;

use App\Services\Auth\JWTService;
use App\Traits\ResponseHandler;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JWTAuthMiddleware
{

    use ResponseHandler;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $authorization = $request->header('Authorization');
            $apiKey = $request->header('x-api-key');

            $jwtAuth = new JWTService();

            $checkToken = $jwtAuth->isAuthValid($authorization, $apiKey);

            if (!$checkToken) {
                return $this->errorResponse(__METHOD__, self::class, null, 'Invalid JWT', 'The provided JWT was invalid.', 'ERROR', 401);
            }

            return $next($request);
        } catch (\Throwable $th) {
            return $this->errorResponse(__METHOD__, self::class, null, 'An unexpected error just happened, check the trace of the error.');
        }
    }
}
