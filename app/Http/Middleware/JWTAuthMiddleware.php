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

            if (!$authorization || !$apiKey) {
                throw new \InvalidArgumentException('Authentication headers are required.', 401);
            }


            $jwtAuth = new JWTService();

            $checkToken = $jwtAuth->isAuthValid($authorization, $apiKey);

            if (!$checkToken) {
                throw new \InvalidArgumentException('Provided credentials are invalid.', 401);
            }

            return $next($request);
        } catch (\InvalidArgumentException $th) {
            return $this->response(httpCode: $th->getCode() ?? 401, methodName: __METHOD__, className: self::class, resultMessage: $th->getMessage());
        } catch (\Throwable $th) {
            return $this->response(httpCode: 500, methodName: __METHOD__, className: self::class, resultMessage: 'An unexpected error just happened, check the trace of the error.');
        }
    }
}
