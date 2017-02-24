<?php

namespace App\UploadFiles\Vk;

use App\Models\VkImages;
use App\UploadFiles\ActivitySerivce;
use App\UploadFiles\MainInterface\UploadInterface;
use GuzzleHttp\Client;

class VkUpload implements UploadInterface
{
    /**
     * @param object $file
     * @param string $path
     * @param array $data
     * @return mixed|void
     */
    public function uploadVideo($file, $path, $data)
    {
        // Получаем токен для отправки апи вконакте
        $token = $this->serviceTableInfo();

        // Формируем url беря все веденные данные
        $url = "https://api.vk.com/method/video.save?name={$data['title']}";
        $url .= count($data['description']) > 2 ? '' : "&description={$data['description']}";
        $url .= $data['wallpost'] == true ? '' : "&wallpost=1";
        $url .= $data['group_id'] == 0 ? '' : "&group_id={$data['group_id']}";
        $url .= $data['album_id'] == 0 ? '' : "&album_id={$data['album_id']}";
        $url .= "&access_token=$token->token&v=5.62";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                "file=" . $file);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);

        curl_close($ch);

        $response = json_decode($data);

        /**
         * Так как первый запрос к апи вк дает лишь url на загрузку видео, то мы вызываем второй метод,
         * который уже делает все, что нам нужно
         * Передаем ему ссылку на загрузку и ссылку на видео
         */
        return $this->uploadVideoSecond($response->response->upload_url, $path, $token);
    }

    /**
     * @param $url
     * @param $path
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     *
     * Метод для загрузки видео
     */
    private function uploadVideoSecond($url, $path, $token)
    {
        // Новый объект guzzle
        $client = new Client();

        // Отправяем видео
        $response = $client->request('POST', $url, [
                'multipart' => [
                        [
                                'name' => 'video_file',
                                'contents' => fopen($path, 'r'),
                        ],
                ],
        ]);

        // Глобальный метод который удаляет видео с директории после его загрузки
        deleteVideoFromStorage($path);

        // Метод, который записывает данные о запросе в базу данных.
        (new ActivitySerivce)->newActivity($token->user_entity_id, $token->vk_user_id, $token->id);

        $data = [
                'response' => $response->getBody()->getContents(),
                'vk_user_id' => $token->vk_user_id
        ];

        return response()->json(['success' => $data]);
    }

    /**
     * @param $images
     * @param $method
     * @param $ids
     * @return mixed
     */
    public function imagesUpload($images, $method, $ids)
    {
        return $this->{$method}($images, $ids);
    }

    /**
     * @param $images
     * @param $ids
     * @return \Illuminate\Http\JsonResponse
     */
    private function albumUpload($images, $ids)
    {
        // Получаем токен для отправки апи вконакте
        $token = $this->serviceTableInfo();

        // Формируем url беря все веденные данные
        $url = "https://api.vk.com/method/photos.getUploadServer?album_id={$ids['albumId']}";
        $url .= count($ids['groupId']) > 2 ? '' : "&group_id={$ids['groupId']}";
        $url .= "&access_token=$token->token&v=5.62";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                "photos_list=image");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);

        curl_close($ch);

        $response = json_decode($data);

        if ( !$response->response ) {
            return response()->json(['error' => 'Not access'], 406);
        }

        /**
         * Так как первый запрос к апи вк дает лишь url на загрузку видео, то мы вызываем второй метод,
         * который уже делает все, что нам нужно
         * Передаем ему ссылку на загрузку и ссылку на видео
         */
        return $this->uploadImageToAlbum($response->response, $token, $images);
    }

    /**
     * @param $response
     * @param $token
     * @param $images
     * @return \Illuminate\Http\JsonResponse
     */
    private function uploadImageToAlbum($response, $token, $images)
    {
        // Новый объект guzzle
        $client = new Client();

        // Переменная для ответа
        $vkResponse = null;

        // Если фотографий больше, чем одна
        if (count((array) $images) > 1) {
            $file1 = (string) '';
            $file2 = (string) '';
            $file3 = (string) '';
            $file4 = (string) '';
            $file5 = (string) '';

            // Для альбома возможна загрузка только 5 фотографий, перебрать все картинки в отправке запросе невозможно
            // поэтому приходится писать так :)
            // Если файл сущестувует, то сохраняем и записываем ссылку в переменную
            foreach ($images as $k => $v) {
                if (0 == $k) $file1 = $v->store('vk_images');
                if (1 == $k) $file2 = $v->store('vk_images');
                if (2 == $k) $file3 = $v->store('vk_images');
                if (3 == $k) $file4 = $v->store('vk_images');
                if (4 == $k) $file5 = $v->store('vk_images');
            }

            // Отправляем запрос, чтобы вк загрузил картинки к себе на сервер
            $vkResponse = $client->request('POST', $response->upload_url, [
                    'multipart' => [
                            [
                                    'name' => 'file1',
                                    'contents' => fopen($file1, 'r'),
                            ],
                            [
                                    'name' => 'file2',
                                    'contents' => fopen($file2, 'r'),
                            ],
                            [
                                    'name' => 'file3',
                                    'contents' => empty($file3) ? '' : fopen($file3, 'r'),
                            ],
                            [
                                    'name' => 'file4',
                                    'contents' => empty($file4) ? '' : fopen($file4, 'r'),
                            ],
                            [
                                    'name' => 'file5',
                                    'contents' => empty($file5) ? '' : fopen($file5, 'r'),
                            ],
                    ],
            ]);

            // Удаляем картинки из директории
            deleteVkImageFromDir([$file1, $file2, $file3, $file4, $file5]);
        } else {
            // Сохраняем картинку в директорию
            $image = '';

            foreach ( $images as $k => $v ) {
                $image = $v->store('vk_images');
            }

            $vkResponse = $client->request('POST', $response->upload_url, [
                    'multipart' => [
                            [
                                    'name' => 'file1',
                                    'contents' => fopen($image, 'r'),
                            ]
                    ],
            ]);

            // Удаляем картинку с директории
            deleteVkImageFromDir($image);
        }

        // Получаем нужные данные
        $vkResponse = json_decode($vkResponse->getBody()->getContents());

        // Отправляем запрос, чтобы вк сохранил все фотографии
        $imagesInVK = $client->request('POST', 'https://api.vk.com/method/photos.save?access_token=' . $token->token, [
                'form_params' => [
                        'photos_list' => stripslashes($vkResponse->photos_list),
                        'hash' => $vkResponse->hash,
                        'aid' => $vkResponse->aid,
                        'server' => $vkResponse->server
                ]
        ]);

        // Метод, который записывает данные о запросе в базу данных.
        (new ActivitySerivce)->newActivity($token->user_entity_id, $token->vk_user_id, $token->id);

        // Сохраняем данные в базу данных
        $dbInfo = $this->saveInfoAlbumImage(json_decode($imagesInVK->getBody()->getContents())->response);

        $data = [
                'response' => $dbInfo,
                'vk_user_id' => $token->vk_user_id
        ];

        return response()->json(['success' => $data], 200);
    }

    /**
     * @param $data
     *
     * Метод для сохранения данных о загруженной/загруженных картинки/картинок
     * @return VkImages
     */
    private function saveInfoAlbumImage($data)
    {
        $savedData = [];

        foreach ( $data as $k => $v ) {
            $table = new VkImages;
            $table->bundle = 'manga_tom';
            $table->height = $v->height;
            $table->width = $v->width;
            $table->album_link = $v->id;
            $table->author_vk_id = $v->owner_id;
            $table->src = $v->src;
            $table->src_big = $v->src_big;
            $table->src_small = $v->src_small;
            $table->src_xbig = !empty($v->src_xbig) ? $v->src_xbig : '';
            $table->src_xxbig = !empty($v->src_xxbig) ? $v->src_xxbig : '';
            $table->save();

            $savedData[$table->id]['id'] = $table->id;
            $savedData[$table->id]['src'] = $table->src;
        }

        return $savedData;
    }

    private function serviceTableInfo()
    {
        return \DB::table('upload_service')
                ->where('bundle', 'vk')
                ->where('user_entity_id', \Auth::user()->id)
                ->first();
    }
}