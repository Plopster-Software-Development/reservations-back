<?php

namespace App\Services;

use App\Http\Requests\CreateRestaurantRequest;
use App\Http\Resources\ApiResponseResource;
use App\Models\Restaurant;
use App\Traits\ResponseHandler;
use App\Traits\Utils;

class RestaurantService
{
    use ResponseHandler, Utils;

    public function createRestaurant(CreateRestaurantRequest $request): ApiResponseResource
    {
        $requestParams = $request->validated();
        $billingInformation = $requestParams['billing_information'];

        unset($requestParams['billing_information']);

        $restaurant = Restaurant::create($requestParams);
        $restaurant->billingInformation()->create($billingInformation);

        return $this->response(httpCode: 200, methodName: __METHOD__, className: self::class, resultMessage: 'Restaurant created successfully.', codeDescription: 'Restaurant created successfully.', data: $restaurant);
    }
}
