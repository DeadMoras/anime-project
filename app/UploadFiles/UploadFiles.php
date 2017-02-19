<?php

// Класс для делегирования вызовов всех доступных методов

namespace App\UploadFiles;

use App\UploadFiles\MainInterface\UploadInterface;

class UploadFiles
{
    // Объект класса
    private $upload;

    /**
     * UploadFiles constructor.
     * @param UploadInterface $upload
     *
     * Метод принимает класс который реализует интерфейс UploadInterface, создает объект и кладет в свойство выше
     */
    public function __construct( UploadInterface $upload )
    {
        $this->upload = $upload;
    }

    /**
     * @param $file
     * @param string $path
     * @param array $data
     * @return mixed
     *
     * Главный метод интерфейса для загрузки видео
     */
    public function upload( $file, string $path, array $data )
    {
        return $this->upload->uploadVideo((object) $file, (string) $path, (array) $data);
    }

    /**
     * @param object $files
     * @param string $method
     * @param array $ids
     * @return mixed
     *
     * Главный метод интерфейса для загрузки картинок
     */
    public function imagesUpload($files, string $method, array $ids)
    {
        return $this->upload->imagesUpload((object) $files, (string) $method, (array) $ids);
    }
}