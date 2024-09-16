<?php

namespace App\Http\Requests\Table;

use App\Http\Enums\TableStatus;
use App\Http\Enums\TableLocation;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class CreateTableRequest extends BaseRequest
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
        //add layout position - top and left and bottom
        return [
            'restaurant_id' => 'required|uuid|exists:restaurants,id',
            'capacity'      => 'required|integer',
            'location'      => [ 'required', Rule::in(TableLocation::cases()) ],
        ];
    }
}
