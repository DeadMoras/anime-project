<?php

namespace App\UploadFiles;

class TokenService
{
    // Свойство хранит в себе код для получения токена
    private $code;

    /**
     * Метод получает токен, формирует url для получения token'a вконтакте и выполняет запрос
     * В итоге, он получает информацию от вк и вызывает метод , чтобы сохранить данные в базу данных
     */
    public function getToken()
    {
        $this->code = request()->get('code');

        $newUrl = 'https://oauth.vk.com/access_token?client_id='.config('uploadfilesdata.vk.app_id');
        $newUrl .= '&client_secret='.config('uploadfilesdata.vk.secure_key');
        $newUrl .= '&redirect_uri=http://anime-music.ru/upload-service/token';
        $newUrl .= '&code='.$this->code;

        $token = file_get_contents($newUrl);

        $this->saveDb($token);

        return redirect('/admin');
    }

    /**
     * @param $token
     */
    private function saveDb($token)
    {
        $oldToken = \DB::table('upload_service')
            ->where('bundle', 'vk')
            ->where('user_entity_id', \Auth::user()->id)
            ->first();

        if ($oldToken) {
            \DB::table('upload_service')
                ->where('id', $oldToken->id)
                ->update(
                    [
                        'token' => json_decode($token)->access_token,
                    ]);
        } else {
            \DB::table('upload_service')
                ->insert(
                    [
                        'token'          => json_decode($token)->access_token,
                        'bundle'         => 'vk',
                        'vk_user_id'     => json_decode($token)->user_id,
                        'user_entity_id' => \Auth::user()->id,
                    ]);
        }
    }
}