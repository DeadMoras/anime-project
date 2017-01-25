<?php

namespace App\UploadFiles;

use App\UploadFiles\Vk\VkUpload;

class UploadDelegator
{
    public function getUpload()
    {
        $file = request()->file('video');

        $path = request()->file('video')->store('videos');

        $mimeType = explode('/', $file->getMimeType())[1];

        if ( 'mpeg' != $mimeType &&
                'mp4' != $mimeType &&
                'ogg' != $mimeType &&
                'quicktime' != $mimeType &&
                'webm' != $mimeType &&
                'x-ms-wmv' != $mimeType &&
                'x-flv' != $mimeType
        ) {
            return response()->json(['error' => 'it`s not a video']);
        }

        $service = new UploadFiles(new VkUpload);

        $data = [
                'title' => (string) request()->input('title'),
                'description' => (string) request()->input('description'),
                'wallpost' => (int) request()->input('wallpost'),
                'group_id' => (int) request()->input('group_id'),
                'album_id' => (int) request()->input('album_id'),
        ];

        return $service->upload((object) $file, (string) $path, (array) $data);
    }
}
