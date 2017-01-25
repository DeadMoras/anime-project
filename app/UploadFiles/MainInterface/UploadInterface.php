<?php

namespace App\UploadFiles\MainInterface;

interface UploadInterface
{
    public function uploadVideo($file, $path, $data);
}