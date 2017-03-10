<?php

namespace App\Http\Controllers\Api\Likes;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\ApiErrors\Errors;
use App\TokenService\TokenService;
use App\TokenService\Components\Token;
use App\Models\Likes;
use Validator;

class LikesApi extends Controller
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
                    ->addObject('error_code', 423)
                    ->addObject(
                        'error_data', $validator->messages())
                    ->getResponse(), 423);
        }

        $token = (new TokenService(new Token()))->checkToken(
            $request->input('access_token'), $userId);

        if ( ! $token) {
            return response()->json(
                $error->changeErrorTrue()
                    ->addObject('error_code', 401)
                    ->addObject('error_data', 'Вы забыли указать токен')
                    ->getResponse(), 401);
        }

        $likesToPost = Like::where(
            [
                ['user_entity_id', $userId],
                ['post_entity_id', $postId],
                ['bundle', $bundle],
            ])
            ->first();

        if (null == $likesToPost) {
            Like::insert(
                [
                    'user_entity_id' => $userId,
                    'post_entity_id' => $postId,
                    'bundle'         => $bundle,
                ]);

            $bundle = "\\App\\Models\\".$bundle;
            $bundle::where('id', $postId)
                ->increment('likes');

            return response()->json(
                $error->addObject(
                    'success', 'Вы успешно оценили запись')
                    ->getResponse(), 200);
        } else {
            return response()->json(
                $error->changeErrorTrue()
                    ->addObject('error_code', 406)
                    ->addObject('error_data', 'Вы уже оценивали эту запись')
                    ->getResponse(), 406);
        }
    }
}
