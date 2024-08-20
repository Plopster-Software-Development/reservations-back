<?php

namespace App\Http\Middleware;

use App\Services\Auth\BasicAuthService;
use App\Traits\ResponseHandler;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthMiddleware
{
    use ResponseHandler;

    /**
     * Handle an incoming request.
     *
     * Must be used only on public API endpoints and once a user is authenticated and obtains a JWT this middleware shouldn't be used anymore.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $authorization = $request->header('Authorization');
            $apiKey = $request->header('x-api-key');

            $basicAuth = new BasicAuthService();

            $isAuthValid = $basicAuth->isAuthValid($authorization, $apiKey);

            if (!$isAuthValid) {
                return $this->errorResponse(__METHOD__, self::class, null, 'Invalid Credentials', 'The provided auth keys where invalid', 'ERROR', 401);
            }

            return $next($request);
        } catch (\Throwable $th) {
            return $this->errorResponse(__METHOD__, self::class, null, 'An unexpected error just happened, check the trace of the error.', null, 'ERROR', 500);
        }
    }
}
