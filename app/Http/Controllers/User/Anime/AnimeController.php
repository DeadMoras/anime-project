<?php

namespace App\Http\Controllers\User\Anime;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnimeController extends Controller
{
    public function getAnime(Request $request)
    {
        return DB::table('anime')
                ->select('anime_image.name as anime_image_name', 'user_image.name as user_image_name', 'anime.*')
                ->leftJoin('images as anime_image', 'anime_image.entity_id', '=', 'anime.id')
                ->leftJoin('user_info', 'user_info.entity_id', '=', 'anime.user_entity_id')
                ->leftJoin('images as user_image', 'user_image.entity_id', '=', 'user_info.entity_id')
                ->skip($request->input('skip'))
                ->where('anime_image.bundle', 'anime')
                ->where('user_image.bundle', 'user')
                ->take(20)
                ->get();
    }
}