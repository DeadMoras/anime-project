<?php

namespace App\Models;

use App\Models\Image as MImage;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image as LImage;

class Image extends Model
{
    public $timestamps = false;

    /**
     * @param bool $userAvatar для аватарки пользователя название формируется по другому
     * @param string $userNameAvatar логин пользователя для формирования аватарки
     * @return \Illuminate\Http\JsonResponse
     *
     * Главный метод для загрузки фотографий
     * Принимает только одну фотографию
     * В качестве объекта request использует глобальный метод request()
     */
    public function uploadImage( bool $userAvatar = false, string $userNameAvatar = '' )
    {
        // Переменная для ответа на запрос
        $data = [
                'error' => true,
        ];

        // Проверка на существование файла. Название картинки всего должно быть 'image'
        if ( request()->hasFile('image') ) {
            $image = request()->file('image');

            // хайден поля, можно свободно изменять высоту, ширину и папку, куда будет сохраняться картинка
            $path = request()->input('path_to_save_image');
            $width = request()->input('image_width');
            $height = request()->input('image_height');

            // Формат картинки
            $imageMimiType = explode('/', $image->getMimeType());

            // Название картинки, если аватарка для пользователя - вызываем другой метод
            $imageName = (string) '';
            if ( true === $userAvatar ) {
                $imageName = $this->userAvatarName($userNameAvatar);
            } else {
                // str2urlImage = метод, который подготавливает название для записи в базу данных
                $imageName = str2urlImage($image->getClientOriginalName());

                // Название картинки для базы данных
                $imagePath = $imageName . time() . '.' . $imageMimiType[1];

                // Сохраняем файл в директорию
                LImage::make($image->getRealPath())
                        ->resize($width, $height)
                        ->save(public_path('images/' . $path . '/' . $imagePath));

                // Информация для базы данных
                $dbData = [
                        'name' => $imagePath,
                        'bundle' => request()->input('image_bundle'),
                        'size' => $image->getSize(),
                        'mimiType' => $image->getMimeType(),
                ];

                // Сохраняем запись о картинке в базу данных
                $data['image'] = $this->saveImageDataBase((array) $dbData);
            }

            $data['error'] = false;
        }

        return $data;
    }

    /**
     * @param string $userNameAvatar
     * @return string
     *
     * Метод который генерирует название фотографии если работа с аватаркой пользователя
     * Имя для фотографии генерируется другим методом , чем для остальных
     * Название формируется с учетом: логина пользователя и его айди, так же, метод вызывает другой метод
     * Который ищет фотографию с таким уже названием и удаляет ее
     * @internal param string $userLogin
     */
    private function userAvatarName( string $userNameAvatar ): string
    {
        // Метод для удаления ранее загруженной аватарки
        $this->userDeleteImage($userNameAvatar);

        return $userNameAvatar;
    }

    /**
     * @param string $userNameAvatar
     * @internal param string $userLogin
     *
     * Метод для удаления ранее загруженной аватарки пользователя
     */
    private function userDeleteImage( string $userNameAvatar )
    {
        // Ищем аватарку пользователя по bundle и name
        // В name - логин и айди пользователя
        $image = MImage::where('bundle', 'users')
                ->where('name', $userNameAvatar)
                ->first();

        if ( null != $image || false != $image ) {
            $this->deleteImageFromDir($image->name . $image->mimetype[1]);
            $image->delete();
        }
    }

    /**
     * @param array $data
     * @return bool
     *
     * Метод для сохранения информации о картинке в базу данных
     */
    private function saveImageDataBase( array $data )
    {
        $image = new MImage;

        $image->name = $data['name'];
        $image->bundle = $data['bundle'] . explode($data['mimiType'][1]);
        $image->size = $data['size'];
        $image->mimetype = $data['mimiType'];
        $image->status = 0;

        $image->save();

        return $image;
    }

    /**
     * @param int $id
     * @param string $name
     *
     * Метод для удаления записи из базы данных
     */
    public function deleteImage( int $id, string $name )
    {
        Image::where('id', $id)->delete();
        $this->deleteImageFromDir($name);
    }

    /**
     * @param string $name
     */
    private function deleteImageFromDir( string $name )
    {
        Storage::delete('images/user/' . $name);
    }
}
