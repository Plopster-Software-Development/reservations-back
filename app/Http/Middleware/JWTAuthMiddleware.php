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

            $jwtAuth = new JWTService();

            $checkToken = $jwtAuth->isAuthValid($authorization);

            if (!$checkToken) {
                return $this->errorResponse('S401JAM', 'ERROR', 'Invalid JWT', 401);
            }

            return $next($request);
        } catch (\Throwable $th) {
            return $this->errorResponse('S500JAM', 'ERROR');
        }
    }
}
