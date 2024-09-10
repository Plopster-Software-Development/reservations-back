<?php

namespace App\Http\Middleware;

use App\Http\Resources\ApiResponseResource;
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
    public function handle(Request $request, Closure $next): Response|ApiResponseResource
    {
        try {
            $authorization = $request->header('Authorization');
            $apiKey = $request->header('x-api-key');

            if (!$authorization || !$apiKey) {
                throw new \InvalidArgumentException('Authentication headers are required.', 401);
            }

            $basicAuth = new BasicAuthService();

            $isAuthValid = $basicAuth->isAuthValid($authorization, apiKey: $apiKey);

            if (!$isAuthValid) {
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
