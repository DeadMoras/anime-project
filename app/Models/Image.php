<?php

namespace App\Models;

use App\Models\Image as MImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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
                $imageName = strOther($image->getClientOriginalName(), 'image');

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
     * Название формируется с учетом: логина пользователя и его айди
     * @internal param string $userLogin
     */
    private function userAvatarName( string $userNameAvatar ): string
    {
        // Метод для удаления ранее загруженной аватарки
        $this->userRenameImage($userNameAvatar);

        return $userNameAvatar;
    }

    /**
     * @param string $userNameAvatar
     * @internal param string $userLogin
     *
     * Метод для удаления ранее загруженной аватарки пользователя
     */
    public function userRenameImage( string $userNameAvatar )
    {
        // Ищем аватарку пользователя по bundle и name
        // В name - логин и айди пользователя
        $image = MImage::where('bundle', 'users')
                ->where('name', $userNameAvatar)
                ->first();

        if ( null != $image || false != $image ) {
            $image->name = $userNameAvatar;
            $image->save();
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
        $image->bundle = $data['bundle'];
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
     * @param string $dir
     */
    public function deleteImageFromDir( string $name, string $dir ): void
    {
        Storage::delete('images/' . $dir . '/' . $name);
    }

    /**
     * Метод для изменения название картинки в папке.
     * Используется (сейчас) для изменения названия аватарки пользователя
     *
     * @param string $oldName
     * @param string $newName
     * @param string $dir
     */
    public function renameAvatarDir( string $oldName, string $newName, string $dir ): void
    {
        Storage::move('images/' . $dir . '/' . $oldName, 'images/' . $dir . '/' . $newName);
    }

    /**
     * @param string $name
     * @param int $imageId
     * @param bool $entityBool
     * @param int $entityId
     *
     * Метод для изменения название картинки.
     * Если $user = true, то в entity_id попадет еще и айди пользователя. Связанно это с тем, что для загрузки аватарки
     * используется другая логика, чем для других картинок.
     */
    public function renameAvatar( string $name, int $imageId, bool $entityBool = false, int $entityId = 0 ): void
    {
        $image = Image::findOrFail($imageId);

        if ( $entityBool == true ) {
            $image->entity_id = $entityId;
        }

        $image->name = $name;
        $image->status = 1;

        $image->save();
    }

    /**
     * Метод для создания записи в случае, если пользователь не загрузил аватарку.
     *
     * Может использоваться в любых случаях.
     * @param string $bundle
     * @param bool $user
     * @return $this
     */
    public function createNewDefaultImage( string $bundle, $user = false )
    {
        if ( true == $user ) {
            $this->name = 'default.jpg';
        } else {
            $this->name = $bundle . time();
        }

        $this->bundle = $bundle;
        $this->save();

        return $this;
    }
}
