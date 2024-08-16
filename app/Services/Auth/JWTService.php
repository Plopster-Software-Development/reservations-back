<?php

namespace App\Services\Auth;

use App\Services\Contracts\IAuthContract;
use App\Traits\ResponseHandler;
use Illuminate\Support\Facades\Storage;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Helpers\Encryption;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use stdClass;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class JWTService implements IAuthContract
{
    use ResponseHandler;

    protected $publicKey;
    protected $privateKey;
    protected $response;
    protected $dbTransactions;

    public function __construct()
    {
        $this->publicKey = Storage::disk('secPub')->get(env('PUBLIC_AUTH_KEY_PATH'));
        $this->privateKey = Storage::disk('secPriv')->get(env('PRIVATE_AUTH_KEY_PATH'));
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
    public function generateToken(string $userId, string $issPath = '/', array $roles = [], array $extraParams = []): string
    {
        try {
            $issuedAt = Carbon::now()->timestamp;
            $expirationTime = Carbon::now()->add(env('JWT_VALIDITY_TIME'), env('TYPE_TIME'))->timestamp;

            $data = [
                'iss' => env('ENV_DOMAIN_NAME') . $issPath,
                'sub' => $userId,
                'roles' => $roles,
                'iat' => $issuedAt,
                'exp' => $expirationTime
            ];

            if (isset($extraParams)) {
                $data['data'] = Crypt::encrypt(json_encode($extraParams, JSON_UNESCAPED_SLASHES));
            }

            return JWT::encode($data, $this->privateKey, 'RS256');
        } catch (\Throwable $e) {
            throw new InternalErrorException($e->getMessage());
        }
    }

    /**
     * Validates and checks the provided JWT token against specified conditions.
     *
     * @param string  $authorization     The JWT token to be validated.
     *
     * @return bool True if the token is valid and meets the conditions, otherwise false.
     */
    public function isAuthValid(string $authorization, string $apiKey): bool
    {
        try {
            $authorization = trim(str_replace(['"', 'Bearer'], '', $authorization));
            $decoded = $this->decodeJwtToken($authorization);

            if (!$this->isValidToken($decoded)) {
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            //todo: catch all exceptions and log it.
            return false;
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
    private function isValidToken(Object $decoded): bool
    {
        return is_object($decoded); //&& isset($obj->prop)
    }
}
