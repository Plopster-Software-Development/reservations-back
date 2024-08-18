<?php

namespace App\Services;

use App\Http\Requests\CreateAPIConsumerRequest;
use App\Http\Resources\APIConsumerResource;
use App\Models\ApiConsumer;
use App\Traits\ResponseHandler;
use App\Traits\Utils;
use Carbon\Carbon;
use Illuminate\Support\Str;

class APIConsumerService
{
    use ResponseHandler, Utils;

    public function createAPIConsumer(CreateAPIConsumerRequest $request)
    {
        $insertParams = [
            'client_secret' => hash('sha256', bin2hex(random_bytes(16))),
            'api_key' => hash('sha256', Str::uuid()->toString() . Carbon::now()->timestamp),
        ];

        $consumer = ApiConsumer::create(array_merge($insertParams, $request->validated()));

        if (!isset($consumer)) {
            return $this->errorResponse('S500CAC');
        }

        return $this->successResponse('S200CAC', 'SUCCESS', 'Consumer Successfully Created', [
            'client_id' => $consumer->id,
            'client_secret' => $insertParams['client_secret'],
            'api_key' => $insertParams['api_key']
        ]);
    }
}
