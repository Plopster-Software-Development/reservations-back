<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRestaurantRequest;
use App\Services\RestaurantService;
use App\Http\Resources\ApiResponseResource;

class RestaurantController extends Controller
{
    public function __construct(private readonly RestaurantService $restaurantService)
    {
    }

    /**
     * Create Restaurant
     * 
     * Creates a new restaurant with the provided parameters
     * 
     * @unauthenticated
     * @param  CreateRestaurantRequest $request
     * @return ApiResponseResource [ *..* ]
     */
    public function store(CreateRestaurantRequest $request): ApiResponseResource
    {
        return $this->restaurantService->createRestaurant(request: $request);
    }
}
