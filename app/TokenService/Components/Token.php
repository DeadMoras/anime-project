<?php

namespace App\TokenService\Components;

use App\Http\ApiErrors\Errors;
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
     * @param int    $id
     *
     * @return array|bool
     */
    public function checkToken(string $token, int $id = 0)
    {
        $user = null;

        if (0 == $id) {
            $user = User::where('remember_token', $token)->first();
        } else {
            $user = User::findOrFail($id);
        }

        if ( ! $user) {
            return ['error' => 'Incorrect id or token'];
        }

        if ($user->remember_token != $token) {
            return ['error' => 'You are not logged in'];
        } else {
            return true;
        }
    }

    /**
     * @param string $token
     * @param int    $id
     *
     * @return array|bool
     */
    public function saveToken(string $token = '', int $id)
    {
        // Токен
        $saveToken = null;

        if (9 > strlen($token)) {
            if ( ! count($this->token)) {
                return ['error' => 'not tocken'];
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
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuth(array $data)
    {
        return (new AuthComponent)->getAuth($data);
    }
}