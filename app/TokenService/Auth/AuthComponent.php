<?php

namespace App\TokenService\Auth;

use App\Models\User;
use App\TokenService\Components\Token;
use App\TokenService\TokenService;
use League\Flysystem\Exception;

class AuthComponent
{
    /**
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function getAuth(array $data)
    {
        if ( !count($data) ) {
            return response()->json(['error' => 'where is data?'], 401);
        }

        $keys = array_keys($data);
        $values = array_values($data);

        $user = User::where($keys[0], '=', $values[0])->first();

        if ( !$user ) {
            return response()->json(['error' => 'Incorrect data'], 401);
        }

        if ( $user->confirmed == 0 ) {
            return response()->json(['error' => 'Вы не активировали аккаунт'], 401);
        }

        if ( 9 < strlen($user->remember_token) ) {
            return response()->json(['error' => 'You are already loggin'], 401);
        }

        if ( password_verify($values[1], $user->password) ) {
            (new TokenService(new Token))->newToken()->saveToken('', $user->id);

            $user = User::findOrFail($user->id);

            return response()->json(['success' => $user], 200);
        } else {
            return response()->json(['error' => 'incorrect password'], 401);
        }
    }
}