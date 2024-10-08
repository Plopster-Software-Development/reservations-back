<?php

namespace App\Http\Requests\Reservation;

use App\Http\Requests\BaseRequest;

class CreateReservationRequest extends BaseRequest
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
            'id client reserva'               => '',
            'numero de personas'              => '',
            'ubicacion mesa'                  => ';',
            'fecha y hora'                    => '',
            'anotacion especial para reserva' => ''
        ];
    }
}
