<?php

namespace App\Http\Controllers\Api\Likes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\ApiErrors\Errors;
use App\TokenService\TokenService;
use App\TokenService\Components\Token;
use App\Models\Anime;
use App\Models\Manga;
use App\Models\Likes;
use Validator;

class LikeController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setLike(Request $request)
    {
        $error = new Errors;

        $validator = Validator::make(
            $request->all(), [
            'user_id'      => 'required', // Айди пользователя
            'post_id'      => 'required', // Айди поста (аниме, манги, коммента...)
            'bundle'       => 'required', // Связь: anime, manga, comment....
            'access_token' => 'required', // Токен пользователя
        ]);

        $userId = $request->input('user_id');
        $postId = $request->input('post_id');
        $bundle = $request->input('bundle');

        if ($validator->fails()) {
            return response()->json(
                $error->changeErrorTrue()
                    ->addObject('error_code', 406)
                    ->addObject(
                        'error_data', $validator->messages()), 406);
        }

        $token = (new TokenService(new Token()))->checkToken(
            $request->input('access_token'), $userId);

        if ( ! $token) {
            return response()->json(
                $error->changeErrorTrue()
                    ->addObject('error_code', 401)
                    ->addObject(
                        'error_data', 'Вы забыли указать токен'), 401);
        }

        $likesToPost = Likes::where(
            [
                ['user_entity_id', $userId,],
                ['post_entity_id', $postId,],
                ['bundle', $bundle,],
            ])
            ->first();

        if (null == $likesToPost) {
            Likes::insert(
                [
                    'user_entity_id' => $userId,
                    'post_entity_id' => $postId,
                    'bundle'         => $bundle,
                ]);

            (string) $bundle::increment('likes');

            return response()->json(
                $error->addObject(
                    'success', 'Вы успешно оценили запись'), 200);
        } else {
            return response()->json(
                $error->changeErrorTrue()
                    ->addObject('error_code', 406)
                    ->addObject(
                        'error_data', 'Вы уже оценивали эту запись')
                    ->getResponse());
        }
    }
}
