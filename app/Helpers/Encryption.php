<?php

namespace App\Helpers;

use App\Helpers\HandleResponse;
use App\Traits\ResponseHandler;
use Illuminate\Support\Facades\Hash;

class Encryption
{
    use ResponseHandler;

    /**
     * Class Encryption
     *
     * This class provides methods for encrypting and checking passwords.
     *
     * @package App\Helpers
     * @author Nicolas David Estevez T.
     * @year 2024
     */
    public function encrypt(string $data)
    {
        $hashedElement = Hash::make($data, [
            'rounds' => 12
        ]);

        if (!$hashedElement) {
            return $this->response(httpCode: 500, methodName: __METHOD__, className: self::class);
        }

        return $hashedElement;
    }

    /**
     * Checks if the provided plaintext password matches the hashed password.
     *
     * @param string $passText The plaintext password.
     * @param string $hashedPassword The hashed password.
     *
     * @return bool True if the plaintext password matches the hashed password, otherwise false.
     */
    public function isValidHash($passText, $hashedPassword)
    {
        return Hash::check($passText, $hashedPassword);
    }
}
