<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    protected $table = 'anime';
    /**
     * @param int $id
     * @param bool $new
     * @return $this
     */
    public function newAnime(int $id = 0, bool $new = true)
    {
        $anime = null;

        if ( true == $new ) {
            $anime = new Anime;
        } elseif ( false == $new ) {
            $anime = Anime::findOrFail($id);
        }

        $anime->name = request()->input('anime_name');
        $anime->status = request()->input('anime_status');
        $anime->year = request()->input('anime_year');
        $anime->age = request()->input('anime_age');
        $anime->same_entity_id = request()->input('sameAnime') ? request()->input('sameAnime') : null;
        $anime->user_entity_id = \Auth::user()->id;

        $anime->save();

        return $anime;
    }

    /**
     * Получение автора аниме
     */
    public function author() {
        return $this->belongsTo('App\Models\User', 'user_entity_id', 'id');
    }
    /**
     * Получение картинки аниме
     */
    public function imagesAnime () {
        return $this->belongsTo('App\Models\Image', 'id', 'entity_id');
    }
}