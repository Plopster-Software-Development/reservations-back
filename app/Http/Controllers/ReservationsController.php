<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\ApiResponseResource;
use App\Services\ReservationService;

class ReservationsController extends Controller
{
    public function __construct(private readonly ReservationService $reservationService)
    {
    }

    public function index(): ApiResponseResource
    {
        $reservations = $this->reservationService->getAll(10);

        return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $reservations);

    }

    public function show(string $id): ApiResponseResource
    {
        $reservation = $this->reservationService->getById($id);

        return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $reservation);
    }

    public function store(CreateReservationRequest $request): ApiResponseResource
    {
        $reservation = $this->reservationService->create($request->all());

        return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $reservation);
    }

    public function update(UpdateReservationRequest $request, string $id): ApiResponseResource
    {
        $reservation = $this->reservationService->update($id, $request->validated());

        return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $reservation);
    }

    public function destroy(string $id): ApiResponseResource
    {
        $reservation = $this->reservationService->delete(id: $id);

        return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $reservation);
    }
}
