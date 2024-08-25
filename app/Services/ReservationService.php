<?php

namespace App\Services;

use App\Models\Reservation;
use App\Traits\ResponseHandler;
use App\Traits\Utils;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ReservationService
{
    use ResponseHandler, Utils;

    public function getAll(int $paginate): LengthAwarePaginator
    {
        return Reservation::paginate($paginate);
    }

    public function getById(string $id): Model|array
    {
        return Reservation::find($id);
    }

    public function create(array $data): Model|array
    {
        return Reservation::create($data);
    }

    public function update(string $id, array $data): Model|array|bool
    {
        $user = Reservation::findOrFail($id);

        return tap($user)->update($data);
    }

    public function delete(int|string $id): bool|array|Model
    {
        $user = Reservation::findOrFail($id);

        return tap($user)->delete();
    }
}
