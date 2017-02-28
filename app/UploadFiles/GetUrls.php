<?php

function vkUrl()
{
    $header = 'https://oauth.vk.com/authorize?client_id='. config('uploadfilesdata.vk.app_id');
    $header .= '&display=popup';
    $header .= '&redirect_uri=http://anime-music.ru/upload-service/token';
    $header .= '&scope=photos,video,audio';
    $header .= '&response_type=code&v=5.62&state=vk';

    return $header;
}