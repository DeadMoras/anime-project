<?php

namespace App\Http\Controllers\Api\Comments\Anime;

use App\Http\ApiErrors\Errors;
use App\Http\Controllers\Api\Comments\Core\CommentsMain;
use App\Http\Controllers\Controller;
use App\Models\AnimeComment;
use App\Models\Notification;
use App\Models\Seo;
use Illuminate\Http\Request;
use Validator;
use App\TokenService\TokenService;
use App\TokenService\Components\Token;

class CommentsApi extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComments(Request $request)
    {
        $error = new Errors;

        $title = $request->input('seo_title');

        $comments = (new CommentsMain)->getComments('AnimeComment', $title);

        if ('error' == $comments[0]) {
            return response()->json(
                $error->changeErrorTrue()
                    ->addObject('error_code', 406)
                    ->addObject('error_code', array_values($comments))
                    ->getResponse(), 406);
        }

        return response()->json(
            $error->addObject('response', $comments)
                ->getResponse(), 200);
    }

    public function addComment(Request $request)
    {
        $error = new Errors;

        $validator = Validator::make(
            $request->all(), [
            'newComment.comment'    => 'required',
            'newComment.user_id'    => 'required',
            'newComment.post_title' => 'required',
            'access_token'          => 'required',
        ]);

        $userId = $request->input('newComment.user_id');

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
                    ->addObject(
                        'error_data', 'Вы забыли указать токен'), 401);
        }

        $data = (new CommentsMain)->addComment('AnimeComment', $request->input('newComment'));

        if ( ! $data) {
            return response()->json(
                $error->changeErrorTrue()
                    ->addObject('error_code', 500)
                    ->addObject('error_data', 'Произошла ошибка')
                    ->getResponse(), 500);
        }

        if (0 != $request->input('newComment')['reply_to_comment']) {
            (new Notification)->newNotify(
                [
                    'to_user_id' => $request->input('newComment')['reply_to_user_id'],
                    'bundle'     => 'anime',
                    'body'       => 'Вам ответили в <a href="'.env('api_url').'/api/comments/read/'
                        .$data[0]['comment_id'].'">комментарии</a>',
                ]);
        }

        return response()->json(
            $error->addObject('response', $data[0])
                ->getResponse(), 200);
    }
}