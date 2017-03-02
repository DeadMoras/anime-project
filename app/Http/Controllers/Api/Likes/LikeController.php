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
        $token = new TokenService(new Token())->checkToken($request->access_tocken, $request->userId);
        if ($token) {
            $ckeckLikes = Likes::where(
                ['user_entity_id', '=' , $request->userId],
                ['post_entity_id', '=' , $request->postId],
                ['bundle', '=' , $request->bundle]
            )->first();
            if ($ckeckLikes === null) {
                $insert = Likes::insert([
                    'user_entity_id', '=' , $request->userId,
                    'post_entity_id', '=' , $request->postId,
                    'bundle', '=' , $request->bundle
                ]);

                switch ($request->bundle) {
                    case 'anime':
                        Anime::increment('likes');
                        return response()->json(['likes add'], 200);
                    break;
                }
            } else {
                return response()->json(
                    new Errors()->changeErrorTrue()
                                ->addObject('error_code', 406)
                                ->addObject('error_data', 'likes has updated')
                                ->getResponse()
                );
            }
        }
        return response()->json(
            new Errors()->changeErrorTrue()
                        ->addObject('error_code', 500)
                        ->addObject('error_data', 'likes has updated')
                        ->getResponse()
        );
    }
}
