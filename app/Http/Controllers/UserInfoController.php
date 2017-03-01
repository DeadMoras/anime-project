<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\UserInfo;

class UserInfoController extends Controller
{
    // Объект класса UserInfo
    public $object = null;

    // Айди текущего пользователя
    public $userId;

    public function __construct()
    {
        $this->userId = \Auth::user()->id;

        if ($this->object == null) {
            $this->object = UserInfo::where('entity_id', $this->userId)
                ->first();
        }

        return $this->object;
    }

    /**
     * @return mixed
     *
     * Возвращает auto_increment в таблице user_info
     */
    public function getId()
    {
        return $this->object->id;
    }

    /**
     * @return mixed
     *
     * Возвращает логин из таблицы user_info
     */
    public function getLogin()
    {
        return $this->object->login;
    }

    /**
     * @return mixed
     *
     * Возвращает sex из таблицы user_info
     */
    public function getSex()
    {
        return $this->object->sex;
    }

    /**
     * @return mixed
     *
     * Возвращает vk из таблицы user_info
     */
    public function getVk()
    {
        return $this->object->vk;
    }

    /**
     * @return mixed
     *
     * Возвращает skype из таблицы user_info
     */
    public function getSkype()
    {
        return $this->object->skype;
    }

    /**
     * @return mixed
     *
     * Возвращает twitter из таблицы user_info
     */
    public function getTwitter()
    {
        return $this->object->twitter;
    }

    /**
     * @return mixed
     *
     * Возвращает facebook из таблицы user_info
     */
    public function getFacebook()
    {
        return $this->object->facebook;
    }

    /**
     * @return mixed
     *
     * Возвращает created_at из таблицы user_info
     */
    public function getCreated()
    {
        return $this->object->created_at;
    }

    /**
     * @return mixed
     *
     * Возвращает updated_at из таблицы user_info
     */
    public function getUpdated()
    {
        return $this->object->updated_at;
    }

    /**
     * @return mixed
     *
     * Возвращает название аватарки пользователя
     */
    public function getAvatar()
    {
        return Image::select('name')
                   ->where('entity_id', $this->userId)
                   ->first()['name'];
    }
}