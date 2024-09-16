<?php

namespace App\Http\Controllers;

use App\Http\Requests\table\CreateTableRequest;
use App\Http\Requests\table\UpdateTableRequest;
use App\Http\Resources\ApiResponseResource;
use App\Services\TableService;

class TablesController extends Controller
{
    public function __construct(private readonly TableService $tableService)
    {
    }

    public function index(): ApiResponseResource
    {
        $tables = $this->tableService->getAll(10);

        return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $tables);
    }

    public function show(string $id): ApiResponseResource
    {
        return $this->tableService->getById($id);
    }

    public function store(CreateTableRequest $request): ApiResponseResource
    {
        return $this->tableService->create($request->validated());
    }

    public function update(UpdateTableRequest $request, string $id): ApiResponseResource
    {
        return $this->tableService->update($id, $request->validated());
    }

    public function destroy(string $id): ApiResponseResource
    {
        return $this->tableService->delete($id);
    }

}
