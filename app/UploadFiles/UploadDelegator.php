<?php

namespace App\UploadFiles;

use App\UploadFiles\Vk\VkUpload;

class UploadDelegator
{
    /**
     * @return \Illuminate\Http\JsonResponse|mixed
     *
     * Метод на который клиент отправляет запрос при загрузке видео.
     * Метод сохраняет файл, проверяет: видео или нет и вызывает метод для загрузки видео нужного класса
     */
    public function getUpload()
    {
        $file = request()->file('video');

        $mimeType = explode('/', $file->getMimeType())[1];

        if ('mpeg' != $mimeType &&
                'mp4' != $mimeType &&
                'ogg' != $mimeType &&
                'quicktime' != $mimeType &&
                'webm' != $mimeType &&
                'x-ms-wmv' != $mimeType &&
                'x-flv' != $mimeType
        ) {
            return response()->json(['error' => 'it`s not a video']);
        }

        // Временное сохранение видео, чтобы можно было передать его
        $path = request()->file('video')->store('videos');

        $service = new UploadFiles(new VkUpload);

        // информация о видео
        $data = [
                'title' => (string) request()->input('title'),
                'description' => (string) request()->input('description'),
                'wallpost' => (int) request()->input('wallpost'),
                'group_id' => (int) request()->input('group_id'),
                'album_id' => (int) request()->input('album_id'),
        ];


        return $service->upload((object) $file, (string) $path, (array) $data);
    }

    /**
     *
     * Главный метод для загрузки фотографий.
     * В $type указывается: на стену, в личные сообщения или же загрузка в альбом
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage()
    {
        $method = request()->input('method');
        $files = request()->file('images');
        $vkIds = request()->input('vkId');

        if ( !$files ) {
            return response()->json(['error' => 'Nothing']);
        }

        $service = new UploadFiles(new VkUpload);

        return $service->imagesUpload((object) $files, (string) $method, (array) $vkIds);
    }
}
