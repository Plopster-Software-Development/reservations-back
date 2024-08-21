<?php

namespace App\Services;

use App\Http\Requests\CreateRestaurantRequest;
use App\Models\Restaurant;
use App\Traits\ResponseHandler;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;


class RestaurantService
{
    use ResponseHandler, Utils;

    public function createRestaurant(CreateRestaurantRequest $request): JsonResponse
    {
        $requestParams = $request->validated();
        $billingInformation = $requestParams['billing_information'];

        unset($requestParams['billing_information']);

        $restaurant = Restaurant::create($requestParams);
        $restaurant->billingInformation()->create($billingInformation);

        return $this->successResponse(__METHOD__, self::class, null, 'Restaurant created successfully.', 'Restaurant created successfully.', null, 200, $restaurant);
    }
}
