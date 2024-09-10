<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponseResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'resultCode'    => $this->resultCode ?? ($this->httpCode === 200 ? 'SUCCESS' : 'ERROR'),
            'resultMessage' => $this->resultMessage ?? ($this->httpCode === 200 ? 'Successful Response' : 'An error occurred while processing the request.'),
            'traceCode'     => $this->traceCode ?? 'Could not get a trace code.',
            'result'        => $this->when($this->result !== null, $this->result),
        ];
    }
}
