<?php

namespace App\Http\Middleware;

use App\Services\Auth\OneTimeCodeService;
use App\Traits\ResponseHandler;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OTCAuthMiddleware
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
            $secretAppKey = $request->header('CheckToken');
            $oneTimeCode = $request->header('SecurityCode');

            $otcAuth = new OneTimeCodeService();

            $isAuthValid = $otcAuth->isAuthValid($secretAppKey, $oneTimeCode);

            if (!$isAuthValid) {
                return $this->errorResponse('S401OAM', 'ERROR', 'Invalid Credentials', 401);
            }

            return $next($request);
        } catch (\Throwable $th) {
            return $this->errorResponse('S500OAM', 'ERROR');
        }
    }
}
