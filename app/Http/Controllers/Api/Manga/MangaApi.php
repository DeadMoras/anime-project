<?php

namespace App\Http\Controllers\Api\Manga;

use App\Http\ApiErrors\Errors;
use App\Http\Controllers\Controller;
use App\Models\Manga;

class MangaApi extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * Возвращает 4 последних добавленных манги для статистики на главную страницу
     */
    public function getMangaStatistic()
    {
        $error = new Errors;

        $data = Manga::with(
            [
                'author'          => function ($q) {
                    $q->select('id');
                },
                'mangaImage'      => function ($q) {
                    $q->select('entity_id', 'name');
                },
            ])
            ->take(4)
            ->get(['id', 'name', 'user_entity_id']);

        $response = [];

        foreach ($data as $k => $v) {
            $response[$v->id]['manga_id'] = $v->id;
            $response[$v->id]['name'] = $v->name;
            $response[$v->id]['author'] = $v->id;
            $response[$v->id]['manga_preview'] = env('app_url').'images/manga/'.$v->mangaImage[0]->name;
        }

        return response()->json(
            $error->addObject('response', $response)
                ->getResponse(), 200);
    }
}