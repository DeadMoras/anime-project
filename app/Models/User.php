<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public function multiOptions($keys)
    {
        if ( request()->input('action') == 'notConfirm' ) {
            User::whereIn('id', $keys)
                ->update(['confirmed' => 0]);
        } elseif ( request()->input('action') == 'confirm' ) {
            User::whereIn('id', $keys)
                ->update(['confirmed' => 1]);
        } elseif ( request()->input('action') == 'delete' ) {
            User::whereIn('id', $keys)
                ->delete();
        }
    }

    /**
     * @param array $data
     * @param int $id
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
     * Получение картинки юзера
     */
    public function imagesUser () {
        return $this->belongsTo('App\Models\Image', 'id', 'entity_id');
    }
}
