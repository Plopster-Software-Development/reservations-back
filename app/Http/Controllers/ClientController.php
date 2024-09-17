<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Resources\ApiResponseResource;
use App\Services\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(private readonly ClientService $clientService)
    {
    }

    public function index()
    {
        $users = $this->clientService->getAll(10);

        return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $users);
    }

    public function show(string $id)
    {
        return $this->clientService->getById($id);
    }

    public function store(CreateClientRequest $request): ApiResponseResource
    {
        return $this->clientService->create($request->validated());
    }

    public function update(UpdateClientRequest $request, string $id): ApiResponseResource
    {
        return $this->clientService->update($id, $request->validated());
    }

    public function destroy(string $id)
    {
        return $this->clientService->delete($id);
    }

}
