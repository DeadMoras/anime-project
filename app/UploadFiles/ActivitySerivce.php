<?php

/**
 * Класс который записывает новую запись в таблицу 'upload_service', при каждой загрузке любого видео
 */

namespace App\UploadFiles;

class ActivitySerivce
{
    public function newActivity(int $userId, int $userVkId, int $entityPost)
    {
        \DB::table('upload_service')
            ->insert(
                [
                    'bundle'         => 'activity',
                    'user_entity_id' => $userId,
                    'vk_user_id'     => $userVkId,
                    'post_entity_id' => $entityPost,
                ]);
    }
}