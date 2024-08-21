<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRestaurantRequest;
use App\Services\RestaurantService;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function __construct(private readonly RestaurantService $restaurantService)
    {
    }

    public function createRestaurant(CreateRestaurantRequest $request)
    {
        return $this->restaurantService->createRestaurant($request);
    }
}
