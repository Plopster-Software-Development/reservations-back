<?php

namespace App\Services\Contracts;

use stdClass;

interface IAuthContract
{
    public function generateToken(string $userId, string $issPath = '/', array $roles = []): string;
    public function isAuthValid(string $authorization): bool;
}
