<?php

namespace App\UploadFiles\Vk;

use App\UploadFiles\MainInterface\UploadInterface;
use GuzzleHttp\Client;

class VkUpload implements UploadInterface
{
    /**
     * @param object $file
     * @param string $path
     * @param array $data
     */
    public function uploadVideo( $file, $path, $data )
    {
        $token = \DB::table('upload_service')
                ->where('bundle', 'vk')
                ->where('user_entity_id', \Auth::user()->id)
                ->first();

        $url = "https://api.vk.com/method/video.save?name={$data['title']}";
        $url .= count($data['description']) > 2 ? '' : "&description={$data['description']}";
        $url .= $data['wallpost'] == 0 ? '' : "&wallpost={$data['wallpost']}";
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

        $this->uploadVideoSecond($response->response->upload_url, $path);
    }

    private function uploadVideoSecond( $url, $path )
    {
        $client = new Client();

        $response = $client->request('POST', $url, [
                'multipart' => [
                        [
                                'name' => 'video_file',
                                'contents' => fopen($path, 'r'),
                        ],
                ],
        ]);

        return response()->json(['success' => $response->getBody()->getContents()]);
    }
}