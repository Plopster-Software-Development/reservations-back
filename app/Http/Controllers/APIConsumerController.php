<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAPIConsumerRequest;
use App\Http\Resources\ApiResponseResource;
use App\Services\APIConsumerService;

class APIConsumerController extends Controller
{
    public function __construct(private readonly APIConsumerService $apiConsumerService)
    {
    }

    public function createAPIConsumer(CreateAPIConsumerRequest $request): ApiResponseResource
    {
        return $this->apiConsumerService->createAPIConsumer($request);
    }
}
