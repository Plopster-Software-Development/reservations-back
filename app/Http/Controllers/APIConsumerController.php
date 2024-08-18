<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAPIConsumerRequest;
use App\Services\APIConsumerService;


class APIConsumerController extends Controller
{
    public function __construct(private readonly APIConsumerService $apiConsumerService) {}

    public function createAPIConsumer(CreateAPIConsumerRequest $request)
    {
        return $this->apiConsumerService->createAPIConsumer($request);
    }
}
