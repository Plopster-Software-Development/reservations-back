<?php

namespace App\Http\Requests;

use App\Traits\ResponseHandler;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\CustomHttpResponseException;

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

        throw new CustomHttpResponseException($this->response(httpCode: 400, methodName: __METHOD__, className: self::class, resultMessage: 'Provided parameters are invalid.', data: $errors));
    }
}