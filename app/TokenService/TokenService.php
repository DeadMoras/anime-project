<?php

namespace App\TokenService;

use App\TokenService\Interfaces\TokenInterface;

class TokenService
{
    private $object;

    /**
     * TokenService constructor.
     * @param TokenInterface $object
     */
    public function __construct(TokenInterface $object)
    {
        $this->object = $object;
    }

    public function newToken()
    {
        $this->object->createToken();

        return $this;
    }

    /**
     * @param string $token
     * @param int $id
     */
    public function checkToken(string $token, int $id)
    {
        return $this->object->checkToken($token, $id);
    }

    /**
     * @param string $token
     * @param int $id
     */
    public function saveToken(string $token, int $id)
    {
        return $this->object->saveToken($token, $id);
    }

    /**
     * @param array $data
     * @return
     */
    public function getAuth(array $data)
    {
        return $this->object->getAuth($data);
    }
}