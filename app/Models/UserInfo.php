<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'user_info';

    /**
     * @param int $entity_id
     *
     * @return string
     *
     * Метод создает новую запись в базе данных.
     * То есть - новый пользователь - новая запись.
     */
    public function newInfo(int $entity_id): string
    {
        $this->entity_id = $entity_id;
        $this->login = request()->input('user.login');
        $this->sex = request()->input('user.sex');
        $this->skype = request()->input('user.registerskype', null);
        $this->vk = request()->input('user.registerVk', null);
        $this->facebook = request()->input('user.registerfacebook', null);
        $this->twitter = request()->input('user.registertwitter', null);

        $this->save();

        return $this->login.$entity_id;
    }

    /**
     * @param array $data
     * @param int   $id
     *
     * Метод для обновления данных пользователя
     */
    public function updateInfo(array $data, int $id)
    {
        $user = UserInfo::where('entity_id', $id)
            ->first();

        $user->login = $data['login'];
        $user->vk = $data['vk'];
        $user->skype = $data['skype'];
        $user->twitter = $data['twitter'];
        $user->facebook = $data['facebook'];
        $user->sex = $data['sex'];

        $user->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * Связь с юзером
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id', 'entity_id');
    }
}
