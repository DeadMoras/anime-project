<?php

namespace App\Http\Controllers\Api\Anime;

use App\Http\ApiErrors\Errors;
use App\Http\Controllers\Controller;
use App\Models\Seo;
use Illuminate\Http\Request;
use App\Models\Anime;

class AnimeApi extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * Возвращает 20 последних добавленных аниме
     */
    public function getAnime()
    {
        $error = new Errors();

        $data = Anime::with(
            [
                'author' => function ($q) {
                    $q->select('id');
                },
                'author.userInfo',
                'author.userImage',
                'animeImage',
                'seo'    => function ($q) {
                    $q->select('entity_id', 'path');
                },
            ])
            ->take(20)
            ->orderBy('created_at', 'desc')
            ->get();

        $response = [];

        foreach ($data as $k => $v) {
            $response[] = [
                'anime_id'      => $v->id,
                'name'          => $v->name,
                'status'        => $v->status,
                'description'   => $v->description,
                'likes'         => $v->likes,
                'age'           => $v->age,
                'year'          => $v->year,
                'visits'        => $v->visits,
                'anime_preview' => env('app_url').'images/anime/'.$v->animeImage[0]->name,
                'created_at'    => $v->created_at,
                'anime_link'    => '/anime/'.$v->seo[0]->path,
                'author'        => [
                    'id'     => $v->author->id,
                    'login'  => $v->author->userInfo->login,
                    'avatar' => env('app_url').'images/user/'.$v->author->userImage[0]->name,
                ],
            ];
        }

        return response()->json(
            $error->addObject('response', $response)
                ->getResponse(), 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * Возвращает 3 самых популярных аниме
     */
    public function getBestAnime()
    {
        $error = new Errors;

        $data = Anime::select('id', 'name', 'likes')
            ->get(3);

        $response = [];

        foreach ($data as $k => $v) {
            $response[$v->id]['anime_id'] = $v->id;
            $response[$v->id]['likes'] = $v->likes;
            $response[$v->id]['name'] = $v->name;
            $response[$v->id]['anime_preview'] = env('app_url').'images/anime/'.$v->animeImage[0]->name;
        }

        usort(
            $response, function ($a, $b) {
            return $b['likes'] - $a['likes'];
        });

        return response()->json(
            $error->addObject('response', $response)
                ->getResponse(), 200);
    }

    /**
     * @param $title
     *
     * Метод возвращает информацию для каждого аниме по seo-title
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEachAnime($title)
    {
        $error = new Errors;

        if ( ! $title) {
            return response()->json(
                $error->changeErrorTrue()
                    ->addObject('error_code', 406)
                    ->addObject('error_data', 'Нету title')
                    ->getResponse(), 406);
        }

        // Достаем айди аниме
        $seo = Seo::where('path', $title)
            ->where('bundle', 'anime')
            ->pluck('entity_id');

        $anime = Anime::with(
            [
                'series',
                'entityGenres.genres'
            ])
            ->where('anime.id', $seo)
            ->first();

        // Связанные аниме
        $sameAnimeDb = Anime::where('id', '<>', $anime->id)
            ->where('same_entity_id', $anime->same_entity_id)
            ->orWhere('same_entity_id', $anime->id)
            ->get();

        $sameAnime = [];

        foreach ( $sameAnimeDb as $k => $v ) {
            $sameAnime[] = [
                'id' => $v->id,
                'anime_name' => $v->name
            ];
        }

        // Аниме серии
        $animeSeries = [];

        foreach ( $anime->series as $k => $v ) {
            $animeSeries[] = [
                'id' => $v->id,
                'link' => $v->link
            ];
        }

        // Жанры
        $genres = [];

        foreach ( $anime->entityGenres as $k => $v ) {
            $genres[] = [
                'genre_id' => $v->genres[0]->id,
                'genre_name' => $v->genres[0]->name
            ];
        }

        $response = [];
        $response['id'] = $anime->id;
        $response['anime_name'] = $anime->name;
        $response['status'] = $anime->status;
        $response['visits'] = $anime->visits;
        $response['description'] = $anime->description;
        $response['likes'] = $anime->likes;
        $response['year'] = $anime->year;
        $response['age'] = $anime->age;
        $response['created_at'] = $anime->created_at;
        $response['anime_preview'] = env('app_url').'/images/anime/'.$anime->animeImage[0]->name;
        $response['series'] = $animeSeries;
        $response['author'] = [
            'id' => $anime->author->id,
            'reputation' => $anime->author->reputation,
            'login' => $anime->author->userInfo->login,
            'sex' => $anime->author->userInfo->sex,
            'avatar' => env('app_url').'/images/user/'.$anime->author->userImage[0]->name
        ];
        $response['genres'] = $genres;

        // Если есть связанные аниме
        if ( $sameAnime ) {
            $response['same_anime'] = $sameAnime;
        }

        return response()->json(
            $error->addObject('response', $response)
                ->getResponse(), 200);
    }
}