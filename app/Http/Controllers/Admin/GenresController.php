<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EntityGenre;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenresController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptGenre(Request $request): \Illuminate\Http\JsonResponse
    {
        $chip = $request->input('chip');
        $bundle = $request->input('bundle');

        $result = Genre::where('name', $chip)->first();

        $newChip = new \stdClass();
        if (!$result) {
            $newChip = $this->newGenre($chip);
        } else {
            $newChip = $result;
        }

        $entityGenre = EntityGenre::create([
                'bundle' => $bundle,
                'genre_entity_id' => $newChip->id
        ]);

        return response()->json(['success' => $entityGenre]);
    }

    /**
     * @param string $chip
     * @return Genre
     */
    private function newGenre(string $chip): Genre
    {
        return Genre::create([
                'name' => $chip
        ]);
    }

    /**
     * @param int $id
     *
     * Возвращает жанры по айди
     * @param string $bundle
     */
    public function getGenres(int $id, string $bundle)
    {
        return \DB::table('genres')
                ->select('genres.name as genres_name', 'entity_genres.id as entity_genres_id')
                ->leftJoin('entity_genres', 'entity_genres.genre_entity_id', '=', 'genres.id')
                ->where('entity_genres.bundle', $bundle)
                ->where('entity_genres.post_entity_id', $id)
                ->get();
    }
}