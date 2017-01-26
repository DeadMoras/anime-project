<?php

/**
 * Интерфейс служит неким удобством для подмены сервисов загрузки видео в дальнейшем
 */

namespace App\UploadFiles\MainInterface;

interface UploadInterface
{
    /**
     * @param $file
     * @param $path
     * @param $data
     * @return mixed
     */
    public function uploadVideo($file, $path, $data);
}