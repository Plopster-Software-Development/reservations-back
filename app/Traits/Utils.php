<?php

namespace App\Traits;

use Illuminate\Support\Facades\Hash;

trait Utils
{
    static function trimParams(array $params, array $elementsToRemove)
    {
        $sanitizedParams = [];

        foreach ($params as $key => $values) {
            $sanitizedValues = array_map(function ($value) use ($elementsToRemove) {
                return trim(str_replace($elementsToRemove, '', $value));
            }, $values);

            $sanitizedParams[$key] = $sanitizedValues;
        }

        return $sanitizedParams;
    }

    static function encrypt(string $data)
    {
        return Hash::make($data, [
            'rounds' => 12
        ]);
    }

    /**
     * Checks if the provided plaintext password matches the hashed password.
     *
     * @param string $passText The plaintext password.
     * @param string $hashedPassword The hashed password.
     *
     * @return bool True if the plaintext password matches the hashed password, otherwise false.
     */
    static function check($passText, $hashedPassword)
    {
        return Hash::check($passText, $hashedPassword);
    }
}
