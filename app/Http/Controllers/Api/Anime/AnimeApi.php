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

        $data = Anime::with([
            'author' => function ($query) {
                $query->select('users.id');
                $query->with([
                    'imagesUser' => function ($query) {
                        $query->where('bundle', '=', 'user')
                            ->select('images.name', 'images.entity_id');
                    },
                ]);
            },
        ])
            ->with([
                'imagesAnime' => function ($query) {
                    $query->where('bundle', '=', 'anime')
                        ->select('images.name', 'images.entity_id');
                },
            ])
            ->take(20)
            ->get();

        return response()->json($error->addObject('response', $data)
            ->getResponse(), 200);
    }
}
