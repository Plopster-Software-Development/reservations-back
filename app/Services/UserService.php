<?php

namespace App\Services;

use App\Http\Resources\ApiResponseResource;
use App\Models\User;
use App\Services\Auth\JWTService;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Services\Contracts\IStandardContract;
use App\Traits\ResponseHandler;
use App\Traits\Utils;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PhpParser\Error;
use Illuminate\Support\Facades\Hash;


class UserService implements IStandardContract
{
    use ResponseHandler, Utils;

    public function getAll(int $paginate): ApiResponseResource
    {
        try {
            $tasks = User::with('restaurant')->paginate($paginate);

            if ($tasks->isEmpty()) {
                throw new ModelNotFoundException('No users found.');
            }

            return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $tasks);
        } catch (ModelNotFoundException $th) {
            return $this->response(httpCode: 404, methodName: __METHOD__, className: self::class, data: [], resultMessage: $th->getMessage());
        } catch (\Throwable $th) {
            return $this->response(httpCode: 500, methodName: __METHOD__, className: self::class, resultMessage: 'An unexpected error just happened, check the trace of the error.');
        }
    }

    public function getById(string $id): ApiResponseResource
    {
        try {
            $user = User::findOrFail($id);

            return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $user);
        } catch (ModelNotFoundException $th) {
            return $this->response(httpCode: 404, methodName: __METHOD__, className: self::class, resultMessage: 'User could not be created.');
        } catch (\Throwable $th) {
            return $this->response(httpCode: 500, methodName: __METHOD__, className: self::class, resultMessage: 'An unexpected error just happened, check the trace of the error.');
        }
    }

    public function create(array $data): ApiResponseResource
    {
        try {
            $user = User::create($data);

            if (!isset($user)) {
                throw new Error('User could not be created.');
            }

            $user->load('restaurant');

            return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $user);
        } catch (\Throwable $th) {
            return $this->response(httpCode: 500, methodName: __METHOD__, className: self::class, resultMessage: $th->getMessage());
        }
    }

    public function update(string $id, array $data): ApiResponseResource
    {
        try {
            $user = User::findOrFail($id);

            $updatedUser = tap($user)->update($data);

            return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $updatedUser);
        } catch (ModelNotFoundException $th) {
            return $this->response(httpCode: 404, methodName: __METHOD__, className: self::class, resultMessage: 'User not found.');
        } catch (\Throwable $th) {
            return $this->response(httpCode: 500, methodName: __METHOD__, className: self::class, resultMessage: $th->getMessage());
        }
    }

    public function delete(int|string $id): ApiResponseResource
    {
        try {
            $user = User::findOrFail($id);

            $deletedUser = tap($user)->delete();

            return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $deletedUser);
        } catch (ModelNotFoundException $th) {
            return $this->response(httpCode: 404, methodName: __METHOD__, className: self::class, resultMessage: 'User not found.');
        } catch (\Throwable $th) {
            return $this->response(httpCode: 500, methodName: __METHOD__, className: self::class, resultMessage: $th->getMessage());
        }
    }

    public function authenticate(array $data): bool|array
    {
        $user = User::with('roles')->where('email', '=', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return false;
        }

        $roles = [];

        foreach ($user->roles as $role) {
            $roles[] = strtolower($role->name);
        }

        return JWTService::generateToken($user->id, $data['consumer_id'], $user->restaurant_id, '/', $roles);
    }
}
