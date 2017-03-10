<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\ApiErrors\Errors;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\TokenService\Components\Token;
use App\TokenService\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /**
     * @param Request $request
     * @param int     $id
     *
     * Ищет пользователя по переданному айди и возвращает информацию
     *
     * @param         $userId
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param int $user_id
     *
     */
    public function getProfileUser(Request $request, int $id, $userId)
    {
        $error = new Errors;

        $user = User::with(
            [
                'commentsReviews.post',
                'userInfo',
                'userImage',
                'userList',
            ])
            ->where('id', $id)
            ->first();

        $response = [];
        $response['user_id'] = $user->id;
        $response['login'] = $user->userInfo->login;
        $response['city'] = $user->userInfo->city;
        $response['reputation'] = $user->reputation;
        $response['role'] = 0 == $user->role
            ? 'Пользователь'
            : (1 == $user->role
                ? 'Редактор'
                : 'Администратор');
        $response['sex'] = $user->userInfo->sex == 0
            ? 'Парень'
            : 'Девушка';
        $response['email'] = $user->email;
        $response['vk'] = $user->userInfo->vk;
        $response['skype'] = $user->userInfo->skype;
        $response['twitter'] = $user->userInfo->twitter;
        $response['facebook'] = $user->userInfo->facebook;
        $response['city'] = $user->userInfo->city;
        $response['created_at'] = $user->userInfo->created_at;
        $response['avatar'] = env('app_url').'/images/user/'.$user->userImage[0]->name;
        $response['list']['watching'] = [];
        $response['list']['will_watch'] = [];
        $response['list']['watched'] = [];
        $response['list']['favorite'] = [];

        // Список аниме юзера
        foreach ($user->userList as $k => $v) {
            if (0 == $v->type) {
                $response['list']['watching'][] = [
                    'list_id'       => $v->id,
                    'anime_id'      => $v->anime[0]->id,
                    'anime_name'    => $v->anime[0]->name,
                    'image_preview' => env('app_url').'/images/anime/'.$v->anime[0]->animeImage[0]->name,
                ];
            } elseif (1 == $v->type) {
                $response['list']['will_watch'][] = [
                    'list_id'       => $v->id,
                    'anime_id'      => $v->anime[0]->id,
                    'anime_name'    => $v->anime[0]->name,
                    'image_preview' => env('app_url').'/images/anime/'.$v->anime[0]->animeImage[0]->name,
                ];
            } elseif (2 == $v->type) {
                $response['list']['watched'][] = [
                    'list_id'       => $v->id,
                    'anime_id'      => $v->anime[0]->id,
                    'anime_name'    => $v->anime[0]->name,
                    'image_preview' => env('app_url').'/images/anime/'.$v->anime[0]->animeImage[0]->name,
                ];
            } elseif (3 == $v->type) {
                $response['list']['favorite'][] = [
                    'list_id'       => $v->id,
                    'anime_id'      => $v->anime[0]->id,
                    'anime_name'    => $v->anime[0]->name,
                    'image_preview' => env('app_url').'/images/anime/'.$v->anime[0]->animeImage[0]->name,
                ];
            }
        }

        // Рецензии юзера
        foreach ($user->commentsReviews as $k => $v) {
            $response['reviews'][] = [
                'anime_id'      => $v->post->id,
                'comment'       => $v->body,
                'anime_preview' => env('app_url').'/images/anime/'.$v->post->animeImage[0]->name,
            ];
        }

        // друзья
        $friends = DB::table('friends')
            ->leftJoin('user_info as from_user_info', 'from_user_info.entity_id', '=', 'friends.from_user_id')
            ->leftJoin('user_info as to_user_info', 'to_user_info.entity_id', '=', 'friends.to_user_id')
            ->leftJoin('images as to_user_image', 'to_user_image.entity_id', '=', 'friends.to_user_id')
            ->leftJoin('images as from_user_image', 'from_user_image.entity_id', '=', 'friends.from_user_id')
            ->where('friends.to_status', 2)
            ->orWhere('friends.to_user_id', $id)
            ->where('friends.from_user_id', $id)
            ->where('friends.from_status', 2)
            ->where('from_user_image.bundle', 'user')
            ->where('from_user_image.status', 1)
            ->where('to_user_image.status', 1)
            ->where('to_user_image.bundle', 'user')
            ->get(
                [
                    'friends.*',
                    'from_user_image.name as from_user_image',
                    'to_user_image.name as to_user_image',
                    'from_user_info.login as from_user_login',
                    'to_user_info.login as to_user_login',
                ]);

        // Текущий (авторизованый) юзер и просмотр профиля
        $currentUserFriend = DB::table('friends')
            ->where('from_user_id', $userId)
            ->orWhere('to_user_id', $userId)
            ->first();

        if ( ! $currentUserFriend) { // не друзья
            $response['current_user_friend'] = false;
        } elseif (2 == $currentUserFriend->from_status && 2 == $currentUserFriend->to_status) { // Друзья
            $response['current_user_friend'] = true;
        } elseif (1 == $currentUserFriend->to_status && 1 == $currentUserFriend->from_status) {
            $response['current_user_friend'] = 'both cancel'; // нужно удалить
        } elseif ($currentUserFriend->from_user_id == $userId && 1 == $currentUserFriend->from_status) {
            $response['current_user_friend'] = 'online user cancel'; // заявку отменил онлайн юзеры
        } elseif ($currentUserFriend->to_user_id == $userId && 1 == $currentUserFriend->to_status) {
            $response['current_user_friend'] = 'online user cancel'; // онлайн юзер ожидает подтверждение (отклонили)
        } elseif ($currentUserFriend->to_user_id == $userId && 0 == $currentUserFriend->to_status) {
            $response['current_user_friend'] = 'online user wait';
        } elseif ($currentUserFriend->from_user_id == $userId && 0 == $currentUserFriend->from_status) {
            $response['current_user_friend'] = 'online user wait';
        }

        $response['friends'] = $friends;

        return response()->json(
            $error->addObject('response', $response)
                ->getResponse(), 200);
    }
}