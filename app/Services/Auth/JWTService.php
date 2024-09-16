<?php

namespace App\Services\Auth;

use App\Models\ApiConsumer;
use App\Services\Contracts\IAuthContract;
use App\Traits\ResponseHandler;
use App\Traits\Utils;
use Illuminate\Support\Facades\Storage;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use stdClass;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use UnexpectedValueException;

class JWTService implements IAuthContract
{
    use ResponseHandler, Utils;

    protected $publicKey;
    protected $response;
    protected $dbTransactions;

    public function __construct()
    {
        $this->publicKey = Storage::disk('auth')->get(env('PUBLIC_AUTH_KEY_PATH'));
    }

    /**
     * Validates and checks the provided JWT token against specified conditions.
     *
     * @param string $userId
     * @param string $issPath
     * @param string $roles
     *
     * @return string returns the JWT
     */
    public static function generateToken(string $userId, string $consumerId, string $restaurant_id, string $issPath = '/', array $roles = [], ?array $extraParams = null): array
    {
        try {
            $currentTime = Carbon::now();
            $expirationTime = $currentTime->copy()->add(
                unit: env('JWT_TYPE_TIME', 'minutes'),
                value: (int) env('JWT_VALIDITY_TIME', 60)
            );

            $data = [
                'iss'            => env('ENV_DOMAIN_NAME') . $issPath,
                'sub'            => $userId,
                'consumer_sub'   => $consumerId,
                'restaurant_sub' => $restaurant_id,
                'roles'          => $roles,
                'iat'            => $currentTime->timestamp,
                'exp'            => $expirationTime->timestamp
            ];

            if ($extraParams) {
                $data['data'] = Crypt::encrypt(json_encode($extraParams, JSON_UNESCAPED_SLASHES));
            }

            $privateKey = Storage::disk('auth')->get(env('PRIVATE_AUTH_KEY_PATH'));

            $jwt = JWT::encode($data, $privateKey, 'RS256');

            $expirationDurationInSeconds = $currentTime->diffInSeconds($expirationTime);

            return [
                'token_type'      => 'Bearer',
                'expiration_time' => (string) $expirationDurationInSeconds,
                'access_token'    => $jwt,
            ];
        } catch (\Throwable $e) {
            Log::error('Error generating token: ' . $e->getMessage());
            throw new InternalErrorException('Error generating token. Please try again later.');
        }
    }


    /**
     * Validates and checks the provided JWT token against specified conditions.
     *
     * @param string  $authorization     The JWT token to be validated.
     *
     * @return bool True if the token is valid and meets the conditions, otherwise false.
     */
    public function isAuthValid(string $authorization, string $apiKey): array
    {
        try {
            $decoded = $this->decodeJwtToken($authorization);

            $model = $this->searchApiConsumer(clientId: $decoded->consumer_sub);

            if (!$this->isValidToken($decoded) || !$this->areCredentialsValid($model, $apiKey)) {
                return [
                    'error' => true,
                ];
            }

            return [
                'error'         => false,
                'restaurant_id' => $decoded->restaurant_sub
            ];
        } catch (UnexpectedValueException $e) {
            return [
                'error' => true,
            ];
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Decodes the provided JWT token using the public key and RS256 algorithm.
     *
     * @param string $jwt The JWT token to be decoded.
     *
     * @return stdClass The JWT's payload as a PHP object
     */
    private function decodeJwtToken(string $jwt): stdClass
    {
        return JWT::decode($jwt, new Key($this->publicKey, 'RS256'));
    }

    /**
     * Checks if the decoded token is valid for merchant authentication.
     *
     * @param object $decoded The decoded JWT token object.
     *
     * @return bool True if the token is valid for merchant authentication, otherwise false.
     */
    private function isValidToken(object $decoded): bool
    {
        return is_object($decoded) && isset($decoded->iss) && isset($decoded->sub)
            && isset($decoded->roles) && isset($decoded->iat) && isset($decoded->exp);
    }

    private function searchApiConsumer(string $clientId): ApiConsumer
    {
        $apiConsumer = ApiConsumer::where('id', $clientId)->firstOrFail();

        if (!$apiConsumer) {
            throw new \Exception('Invalid API consumer.');
        }

        return $apiConsumer;
    }

    private function areCredentialsValid(ApiConsumer $model, string $apiKey): bool
    {
        return $this::check($apiKey, $model->api_key);
    }
}
