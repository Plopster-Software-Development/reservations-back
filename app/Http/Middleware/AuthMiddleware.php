<?php

namespace App\Http\Middleware;

use App\Http\Controllers\JWTAuthController;
use App\Traits\ResponseHandler;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
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

            $jwtAuth = new JWTAuthController();

            $checkToken = $jwtAuth->checkToken($authorization);

            if (!$checkToken) {
                return $this->errorResponse('S401AUM', 'ERROR', $checkToken ?? 'Invalid Credentials', 401);
            }

            return $next($request);
        } catch (\Throwable $th) {
            return $this->errorResponse('S500AUM', 'ERROR');
        }
    }
}
