<?php

namespace App\Http\Controllers;

use App\Exceptions\JwtValidateException;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Support\Facades\Storage;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use App\Helpers\HandleResponse;
use App\Helpers\Encryption;
use DomainException;
use Firebase\JWT\BeforeValidException;
use Illuminate\Http\Request;
use InvalidArgumentException;
use stdClass;
use UnexpectedValueException;

class JWTAuthController extends Controller
{

    protected $publicKey;
    protected $privateKey;
    protected $response;
    protected $dbTransactions;
    protected $checkEnc;

    public function __construct()
    {
        $this->publicKey = Storage::disk('secPub')->get(env('PUBLIC_AUTH_KEY_PATH'));
        $this->privateKey = Storage::disk('secPriv')->get(env('PRIVATE_AUTH_KEY_PATH'));
        $this->checkEnc = new Encryption;
    }

    /**
     * Validates and checks the provided JWT token against specified conditions.
     *
     * @param string  $authorization     The JWT token to be validated.
     *
     * @return mixed True if the token is valid and meets the conditions, otherwise false.
     */
    public function checkToken(string $authorization)
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
    private function isValidToken(Object $decoded)
    {
        return is_object($decoded); //&& isset($obj->prop)
    }
}
