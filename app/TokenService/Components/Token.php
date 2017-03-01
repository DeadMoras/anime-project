<?php

namespace App\TokenService\Components;

use App\Models\User;
use App\TokenService\Auth\AuthComponent;
use App\TokenService\Interfaces\TokenInterface;
use League\Flysystem\Exception;

class Token implements TokenInterface
{
    // Хранит в себе токен
    private $token;

    /*
     * Функция для генерации токена
     */
    public function createToken(): void
    {
        $this->token = generateKey();
    }

    /**
     * @param string $token
     * @param int $id
     *
     * @return array|bool
     */
    public function checkToken(string $token, int $id)
    {
        if ( 1 > count($id) ) {
            return ['error' => 'Where is id'];
        }

        $user = User::findOrFail($id);

        if ( !$user ) {
            return ['error' => 'Incorrect id'];
        }

        if ( $user->remember_token != $token ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param string $token
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function saveToken(string $token = '', int $id): bool
    {
        // Токен
        $saveToken = null;

        if ( 9 > strlen($token) ) {
            if ( !count($this->token) ) {
                throw new Exception('Нету токена');
            }

            $saveToken = $this->token;
        } else {
            $saveToken = $token;
        }

        $user = User::findOrFail($id);
        $user->remember_token = $saveToken;
        $user->save();

        return true;
    }

    /**
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuth(array $data)
    {
        return (new AuthComponent)->getAuth($data);
    }
}