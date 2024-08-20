<?php

namespace App\Services\Auth;

use App\Models\ApiConsumer;
use App\Models\Miscellaneous;
use App\Services\Contracts\IAuthContract;
use App\Traits\Utils;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use stdClass;

class BasicAuthService implements IAuthContract
{
    use Utils;

    public function isAuthValid(string $authorization, string $apiKey): bool
    {
        try {
            $basicCreds = $this->extractBasicCredentials($authorization);
            $model = $this->searchApiConsumer($basicCreds['clientID']);

            if (!$this->areCredentialsValid($model, $basicCreds['clientSecret'], $apiKey)) {
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function extractBasicCredentials($authorization): array
    {
        $credentials = base64_decode($authorization);
        $arrCredentials = explode(':', $credentials);
        return [ 'clientID' => $arrCredentials[0], 'clientSecret' => $arrCredentials[1] ];
    }

    private function searchApiConsumer(string $clientId): ApiConsumer
    {
        $apiConsumer = ApiConsumer::where('id', $clientId)->first();

        if (!$apiConsumer) {
            throw new \Exception('Invalid API consumer.');
        }

        return $apiConsumer;
    }

    private function areCredentialsValid(ApiConsumer $model, string $clientSecret, string $apiKey): bool
    {
        return $this::check($clientSecret, $model->client_secret) && $this::check($apiKey, $model->api_key);
    }
}
