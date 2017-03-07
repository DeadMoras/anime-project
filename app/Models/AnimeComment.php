<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimeComment extends Model
{
    protected $table = 'anime_comments';

    public $timestamps = true;

    /**
     * связь с аниме
     */
    public function post()
    {
        return $this->hasOne('App\Models\Anime', 'id', 'post_entity_id');
    }

    /**
     * Связь с пользователем
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_entity_id');
    }

    /**
     * Связь с юзером в случае ответа
     */
    public function answerUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'answer_to_user_id');
    }

    /**
     * Связь с комментарием в случае ответа
     */
    public function answerComment()
    {
        return $this->hasOne('App\Models\AnimeComment', 'id', 'answer_comment_id');
    }
}