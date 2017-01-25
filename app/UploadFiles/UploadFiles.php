<?php

namespace App\UploadFiles;

use App\UploadFiles\MainInterface\UploadInterface;

class UploadFiles
{
    private $upload;

    public function __construct( UploadInterface $upload )
    {
        $this->upload = $upload;
    }

    public function upload( $file, string $path, array $data )
    {
        return $this->upload->uploadVideo((object) $file, (string) $path, (array) $data);
    }
}