<?php

namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IBaseContract
{
    public function getAll(): LengthAwarePaginator;

    public function getById(string $id): Model|array;

    public function create(array $data): Model|array;

    public function update(string $id, array $data): Model|array|bool;

    public function delete(int|string $id): bool|array|Model;
}
