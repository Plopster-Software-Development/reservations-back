<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateUserRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\ApiResponseResource;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function index()
    {
        $users = $this->userService->getAll(10);

        return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $users);
    }

    public function show(string $id)
    {
        return $this->userService->getById($id);
    }

    public function store(CreateUserRequest $request): ApiResponseResource
    {
        return $this->userService->create($request->validated());
    }

    public function update(UpdateUserRequest $request, string $id): ApiResponseResource
    {
        return $this->userService->update($id, $request->validated());
    }

    public function destroy(string $id)
    {
        return $this->userService->delete($id);
    }

    public function authenticate(AuthenticateUserRequest $request)
    {
        $jwt = $this->userService->authenticate($request->validated());

        if (!$jwt) {
            return $this->response(httpCode: 401, methodName: __METHOD__, className: self::class, resultMessage: 'Invalid Auth Credentials');

        }

        return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $jwt);
    }
}
