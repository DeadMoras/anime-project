<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public function multiOptions($keys)
    {
        if (request()->input('action') == 'notConfirm') {
            User::whereIn('id', $keys)
                ->update(['confirmed' => 0]);
        } elseif (request()->input('action') == 'confirm') {
            User::whereIn('id', $keys)
                ->update(['confirmed' => 1]);
        } elseif (request()->input('action') == 'delete') {
            User::whereIn('id', $keys)
                ->delete();
        }
    }

    /**
     * @param array $data
     * @param int   $id
     */
    public function updateInfo(array $data, int $id)
    {
        $user = User::findOrFail($id);

        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->confirmed = $data['confirmed'];

        $user->save();
    }

    /**
     * Связь с опубликованными аниме
     */
    public function anime()
    {
        return $this->hasMany('App\Models\Anime', 'user_entity_id', 'id');
    }

    /**
     * Связь с картинками
     */
    public function userImage()
    {
        return $this->morphMany('App\Models\Image', 'entity', 'bundle')
            ->where('status', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     *
     * Связь с информацией о юзере
     */
    public function userInfo()
    {
        return $this->hasOne('App\Models\UserInfo', 'entity_id', 'id');
    }
}
