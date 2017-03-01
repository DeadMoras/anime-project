<?php

namespace App\TokenService\Auth;

use App\Http\ApiErrors\Errors;
use App\Models\User;
use App\TokenService\Components\Token;
use App\TokenService\TokenService;
use League\Flysystem\Exception;

class AuthComponent
{
    /**
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function getAuth(array $data)
    {
        $error = new Errors;

        if ( ! count($data)) {
            return response()->json($error->changeErrorTrue()
                ->addObject('error_code', 401)
                ->addObject('error_data', 'Вы не передали данные'), 401);
        }

        $keys = array_keys($data);
        $values = array_values($data);

        $user = User::where($keys[0], '=', $values[0])->first();

        if ( ! $user) {
            return response()->json($error->changeErrorTrue()
                ->addObject('error_code', 401)
                ->addObject('error_data', 'Проверьте правильность данных'),
                401);
        }

        if ($user->confirmed == 0) {
            return response()->json($error->changeErrorTrue()
                ->addObject('error_code', 401)
                ->addObject('error_data', 'Вы не активировали аккаунт'),
                401);
        }

        if (9 < strlen($user->remember_token)) {
            return response()->json($error->changeErrorTrue()
                ->addObject('error_code', 406)
                ->addObject('error_data', 'Вы уже авторизованы'),
                406);
        }

        if (password_verify($values[1], $user->password)) {
            (new TokenService(new Token))->newToken()->saveToken('', $user->id);

            $user = User::findOrFail($user->id);

            return response()->json($error->addObject('response', $user)
                ->getResponse(), 200);
        } else {
            return response()->json($error->changeErrorTrue()
                ->addObject('error_code', 401)
                ->addObject('error_data', 'Проверьте правильность пароля'),
                401);
        }
    }
}