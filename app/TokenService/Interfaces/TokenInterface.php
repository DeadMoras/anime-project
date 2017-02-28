<?php

namespace App\TokenService\Interfaces;

interface TokenInterface
{
    public function createToken();

    public function checkToken(string $token, int $id);

    public function saveToken(string $token, int $id);

    public function getAuth(array $data);
}