<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    protected $table = 'anime';

    /**
     * @param int  $id
     * @param bool $new
     *
     * @return $this
     */
    public function newAnime(int $id = 0, bool $new = true)
    {
        $anime = null;

        if (true == $new) {
            $anime = new Anime;
        } elseif (false == $new) {
            $anime = Anime::findOrFail($id);
        }

        $anime->name = request()->input('anime_name');
        $anime->status = request()->input('anime_status');
        $anime->year = request()->input('anime_year');
        $anime->age = request()->input('anime_age');
        $anime->same_entity_id = request()->input('sameAnime')
            ? request()->input('sameAnime')
            : null;
        $anime->user_entity_id = \Auth::user()->id;

        $anime->save();

        return $anime;
    }

    /**
     * Связь с юзером
     */
    public function author()
    {
        return $this->belongsTo('App\Models\User', 'user_entity_id');
    }

    /**
     * Связь с seo модулем
     */
    public function seo()
    {
        return $this->morphMany('App\Models\Seo', 'entity', 'bundle');
    }

    /**
     * Связь для таблицы картинок
     */
    public function animeImage()
    {
        return $this->morphMany('App\Models\Image', 'entity', 'bundle')
            ->where('status', 1);
    }

    /**
     * Связь с сериями
     */
    public function series()
    {
        return $this->hasMany('App\Models\AnimeSeries', 'entity_id', 'id');
    }

    /**
     * Связь с жанрами
     */
    public function entityGenres()
    {
        return $this->hasMany('App\Models\EntityGenre', 'post_entity_id', 'id')
            ->where('bundle', 'anime');
    }

    /**
     * связь с комментариями
     */
    public function comments()
    {
        return $this->hasMany('App\Models\AnimeComments', 'post_entity_id', 'id');
    }
}