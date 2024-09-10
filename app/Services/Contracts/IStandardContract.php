<?php

namespace App\Services\Contracts;

use App\Http\Resources\ApiResponseResource;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IStandardContract
{
    public function getAll(int $paginate): ApiResponseResource;

    public function getById(string $id): ApiResponseResource;

    public function create(array $data): ApiResponseResource;

    public function update(string $id, array $data): ApiResponseResource;

    public function delete(int|string $id): ApiResponseResource;
}