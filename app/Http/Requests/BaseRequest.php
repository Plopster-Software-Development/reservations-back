<?php

namespace App\Http\Requests;

use App\Http\Resources\ApiResponseResource;
use App\Traits\ResponseHandler;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class BaseRequest extends FormRequest
{

    use ResponseHandler;

    /**
     * Convertir ApiResponseResource a JsonResponse
     *
     * @param ApiResponseResource $apiResponseResource
     * @return JsonResponse
     */
    protected function convertToResponse(ApiResponseResource $apiResponseResource): JsonResponse
    {
        // Asumimos que ApiResponseResource tiene una propiedad 'resource' que contiene los datos de respuesta.
        return new JsonResponse((array) $apiResponseResource->resource, $apiResponseResource->resource->httpCode);
    }

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

        $apiResponseResource = $this->response(
            httpCode: 400,
            methodName: $this::class . "::rules",
            className: $this::class,
            resultMessage: 'Provided parameters are invalid.',
            data: $errors
        );

        throw new HttpResponseException($this->convertToResponse($apiResponseResource));
    }

}