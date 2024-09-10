<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateUserRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
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
        $user = $this->userService->getById($id);

        return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $user);
    }

    public function store(CreateUserRequest $request)
    {
        $user = $this->userService->create($request->all());

        return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $user);
    }

    public function update(UpdateUserRequest $request, string $id)
    {
        $user = $this->userService->update($id, $request->validated());

        return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $user);
    }

    public function destroy(string $id)
    {
        $user = $this->userService->delete($id);

        return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $user);
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
