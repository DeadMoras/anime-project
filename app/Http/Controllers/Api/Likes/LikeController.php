<?php

namespace App\Http\Controllers\Api\Likes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\ApiErrors\Errors;
use App\TokenService\TokenService;
use App\TokenService\Components\Token;
use App\Models\Anime;
use App\Models\Likes;

class LikeController extends Controller
{
    public function setLike (Request $request)
    {
        $error = new Errors();
        $service = new TokenService(new Token());
        $token = $service->checkToken($request->access_tocken, $request->userId);
        if ($token) {
            $ckeckLikes = Likes::where([
                ['user_entity_id', '=' , $request->userId],
                ['post_entity_id', '=' , $request->userId],
                ['bundle', '=' , $request->bundle]
            ])->first();
            if ($ckeckLikes === null) {
                $insert = Likes::insert([
                    'user_entity_id' => $request->userId,
                    'post_entity_id'  =>  $request->postId,
                    'bundle'  =>    $request->bundle
                ]);

                switch ($request->bundle) {
                    case 'anime':
                        Anime::increment('likes');
                        return response()->json(
                            $error->addObject('response', 'like is add')
                                    ->getResponse(), 200
                        );
                    break;
                }
            } else {
                return response()->json(
                    $error->changeErrorTrue()
                            ->addObject('error_code', 406)
                            ->addObject('error_data', 'likes has updated')
                            ->getResponse()
                );
            }
        }
        return response()->json(
            $error->changeErrorTrue()
                    ->addObject('error_code', 401)
                    ->addObject('error_data', 'unAuth')
                    ->getResponse()
        );
    }
}
