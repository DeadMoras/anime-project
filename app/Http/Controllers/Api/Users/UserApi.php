<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\ApiErrors\Errors;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\TokenService\Components\Token;
use App\TokenService\TokenService;
use Illuminate\Http\Request;

class UserApi extends Controller
{
    /**
     * @param Request $request
     *
     * Ищет пользователя по токену и возвращает информацию о нем.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfoUser(Request $request)
    {
        $errors = new Errors;

        $accessToken = $request->input('access_token');
        $userId = $request->input('user_id')
            ? $request->input('user_id')
            : 0;

        if ( ! $accessToken) {
            return response()->json(
                $errors->changeErrorTrue()
                    ->addObject('error_code', 401)
                    ->addObject('error_data', 'Not auth')
                    ->getResponse(), 401);
        }

        $token = (new TokenService(new Token))->checkToken($accessToken, $userId);

        if (is_array($token)) {
            return response()->json(
                $errors->changeErrorTrue()
                    ->addObject('error_code', 406)
                    ->addObject('error_data', $token['error'])
                    ->getResponse(), 406);
        }

        $user = null;

        if (0 == $userId) {
            $user = User::with('userInfo', 'userImage')
                ->where('remember_token', $accessToken)
                ->first();
        } else {
            $user = User::findOrFail($userId);
        }

        $response = [];

        $response['id'] = $user->id;
        $response['avatar'] = env('app_url').'/images/user/'.$user->userImage[0]->name;
        $response['created_at'] = $user->created_at;
        $response['email'] = $user->email;
        $response['role'] = 0 == $user->role
            ? 'user'
            : (1 == $user->role
                ? 'redactor'
                : 'admin');
        $response['login'] = $user->userInfo->login;
        $response['sex'] = $user->userInfo->sex == 0
            ? 'man'
            : 'wooman';
        $response['vk'] = $user->userInfo->vk;
        $response['facebook'] = $user->userInfo->facebook;
        $response['twitter'] = $user->userInfo->twitter;
        $response['skype'] = $user->userInfo->skype;

        return response()->json(
            $errors->addObject('response', $response)
                ->getResponse(), 200);
    }
}