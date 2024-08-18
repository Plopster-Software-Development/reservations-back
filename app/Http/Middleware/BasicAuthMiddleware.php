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
                return $this->errorResponse('S401BAM', 'ERROR', 'Invalid Credentials', 401);
            }

            return $next($request);
        } catch (\Throwable $th) {
            return $this->errorResponse('S500BAM', 'ERROR');
        }
    }
}
