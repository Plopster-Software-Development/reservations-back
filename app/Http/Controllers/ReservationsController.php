<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    public function __construct(private readonly ReservationService $reservationService)
    {
    }

    public function index()
    {
        $reservations = $this->reservationService->getAll(10);

        return $this->successResponse(__METHOD__, self::class, $reservations);
    }

    public function show(string $id)
    {
        $reservation = $this->reservationService->getById($id);

        return $this->successResponse(__METHOD__, self::class, $reservation);
    }

    public function store(CreateReservationRequest $request)
    {
        $reservation = $this->reservationService->create($request->all());

        return $this->successResponse(__METHOD__, self::class, $reservation);
    }

    public function update(UpdateReservationRequest $request, string $id)
    {
        $reservation = $this->reservationService->update($id, $request->validated());

        return $this->successResponse(__METHOD__, self::class, $reservation);
    }

    public function destroy(string $id)
    {
        $reservation = $this->reservationService->delete($id);

        return $this->successResponse(__METHOD__, self::class, $reservation);
    }
}
