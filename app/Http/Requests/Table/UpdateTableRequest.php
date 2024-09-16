<?php

namespace App\Http\Requests\Table;

use App\Http\Enums\TableStatus;
use App\Http\Enums\TableLocation;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateTableRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'table_name' => 'sometimes|string',
            'capacity'   => 'sometimes|integer',
            'location'   => [ 'sometimes', Rule::in(TableLocation::cases()) ],
            'status'     => [ 'sometimes', Rule::in(TableStatus::cases()) ]
        ];
    }
}
