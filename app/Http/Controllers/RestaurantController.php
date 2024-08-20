<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRestaurantRequest;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function __construct(private readonly RestaurantService $restaurantService)
    {
    }

    public function createRestaurant(CreateRestaurantRequest $request)
    {
        return $this->restaurantService->createAPIConsumer($request);
    }
}
