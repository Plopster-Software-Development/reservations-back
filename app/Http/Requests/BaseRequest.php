<?php

namespace App\Http\Requests;

use App\Traits\ResponseHandler;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{

    use ResponseHandler;

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException($this->errorResponse('App/Http/Controllers/RestaurantController::createRestaurant', 'App/Http/Controllers/RestaurantController', null, 'Invalid request parameters.', 'Provided Parameters Where Invalid', 'ERROR', 400, $errors));
    }
}