<?php

namespace App\UploadFiles\Vk;

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
     */
    private function albumUpload($images, $ids)
    {
        dd($images);
    }

    /**
     * @param $images
     */
    private function wallUpload($images)
    {

    }

    /**
     * @param $images
     */
    private function messagesUpload($images)
    {

    }

    private function serviceTableInfo()
    {
        return \DB::table('upload_service')
                ->where('bundle', 'vk')
                ->where('user_entity_id', \Auth::user()->id)
                ->first();
    }
}