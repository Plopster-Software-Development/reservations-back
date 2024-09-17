<?php

namespace App\Services;

use App\Http\Resources\ApiResponseResource;
use App\Models\Client;
use App\Models\Table;
use App\Services\Contracts\IStandardContract;
use App\Traits\ResponseHandler;
use App\Traits\Utils;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PhpParser\Error;


class ClientService implements IStandardContract
{
    use ResponseHandler, Utils;

    public function getAll(int $paginate): ApiResponseResource
    {
        try {
            $tables = Table::with('restaurant')->paginate($paginate);

            if ($tables->isEmpty()) {
                throw new ModelNotFoundException('No tables found.');
            }

            return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $tables);
        } catch (ModelNotFoundException $th) {
            return $this->response(httpCode: 404, methodName: __METHOD__, className: self::class, data: [], resultMessage: $th->getMessage());
        } catch (\Throwable $th) {
            return $this->response(httpCode: 500, methodName: __METHOD__, className: self::class, resultMessage: 'An unexpected error just happened, check the trace of the error.');
        }
    }

    public function getById(string $id): ApiResponseResource
    {
        try {
            $table = Table::findOrFail($id);

            return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $table);
        } catch (ModelNotFoundException $th) {
            return $this->response(httpCode: 404, methodName: __METHOD__, className: self::class, resultMessage: 'Table could not be created.');
        } catch (\Throwable $th) {
            return $this->response(httpCode: 500, methodName: __METHOD__, className: self::class, resultMessage: 'An unexpected error just happened, check the trace of the error.');
        }
    }

    public function create(array $data): ApiResponseResource
    {
        try {
            $data = array_merge($data, [ 'status' => 'available' ]);

            $table = Client::create($data);

            if (!isset($table)) {
                throw new Error('Table could not be created.');
            }

            $table->load('restaurant');

            return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $table);
        } catch (\Throwable $th) {
            return $this->response(httpCode: 500, methodName: __METHOD__, className: self::class, resultMessage: $th->getMessage());
        }
    }

    public function update(string $id, array $data): ApiResponseResource
    {
        try {
            $table = Table::findOrFail($id);

            $updatedTable = tap($table)->update($data);

            return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $updatedTable);
        } catch (ModelNotFoundException $th) {
            return $this->response(httpCode: 404, methodName: __METHOD__, className: self::class, resultMessage: 'Table not found.');
        } catch (\Throwable $th) {
            return $this->response(httpCode: 500, methodName: __METHOD__, className: self::class, resultMessage: $th->getMessage());
        }
    }

    public function delete(int|string $id): ApiResponseResource
    {
        try {
            $table = Table::findOrFail($id);

            $deletedTable = tap($table)->delete();

            return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, data: $deletedTable);
        } catch (ModelNotFoundException $th) {
            return $this->response(httpCode: 404, methodName: __METHOD__, className: self::class, resultMessage: 'Table not found.');
        } catch (\Throwable $th) {
            return $this->response(httpCode: 500, methodName: __METHOD__, className: self::class, resultMessage: $th->getMessage());
        }
    }
}
