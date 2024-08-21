<?php

namespace App\Http\Requests;

class CreateRestaurantRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                               => 'required|string',
            'kitchen_type'                       => 'required|string',
            'billing_information'                => 'required',
            'billing_information.owner_name'     => 'required|string',
            'billing_information.owner_lastname' => 'required|string',
            'billing_information.address'        => 'required|string',
            'billing_information.city'           => 'required|string',
            'billing_information.province'       => 'required|string',
            'billing_information.country'        => 'required|string',
            'billing_information.zip_code'       => 'required|string',
            'billing_information.phoneNumber'    => 'required|string',
            'billing_information.email'          => 'required|email'
        ];
    }
}
