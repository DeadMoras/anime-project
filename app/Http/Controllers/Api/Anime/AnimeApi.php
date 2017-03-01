<?php

namespace App\Http\Controllers\Api\Anime;

use App\Http\ApiErrors\Errors;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anime;

class AnimeApi extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * получение аниме
     */
    public function getAnime()
    {
        $error = new Errors();

        $data = Anime::with(
            [
                'author'           => function ($q) {
                    $q->select('id');
                },
                'author.userInfo'  => function ($q) {
                    $q->select('entity_id', 'login');
                },
                'author.userImage' => function ($q) {
                    $q->select('entity_id', 'name as link');
                },
                'animeImage'       => function ($q) {
                    $q->select('entity_id', 'name as link');
                },
            ])
            ->take(1)
            ->get();

        return response()->json(
            $error->addObject('response', $data)
                ->getResponse(), 200);
    }
}