<?php

namespace App\Services;

use App\Models\User;
use App\Services\Auth\JWTService;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Services\Contracts\IStandardContract;
use App\Traits\ResponseHandler;
use App\Traits\Utils;
use PhpParser\Error;
use Illuminate\Support\Facades\Hash;


class UserService implements IStandardContract
{
    use ResponseHandler, Utils;

    public function getAll(int $paginate): LengthAwarePaginator
    {
        return User::paginate($paginate);
    }

    public function getById(string $id): Model|array
    {
        return User::find($id);
    }

    public function create(array $data): Model|array
    {
        return User::create($data);
    }

    public function update(string $id, array $data): Model|array|bool
    {
        $user = User::findOrFail($id);

        return tap($user)->update($data);
    }

    public function delete(int|string $id): bool|array|Model
    {
        $user = User::findOrFail($id);

        return tap($user)->delete();
    }

    public function authenticate(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return false;
        }

        $roles = $user->roles();

        $jwtService = new JWTService();
        //dd($roles);
        return $jwtService->generateToken($user->id, '/', $roles);
    }
}
