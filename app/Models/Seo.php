<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $table = 'seo';

    public $timestamps = false;

    /**
     * @param array $data
     * @return Mseo
     *
     * Метод для создания новой записи в базе данных
     */
    public function newSeo(array $data)
    {
        $seo = new Seo;

        $seo->bundle = $data['bundle'];
        $seo->seo_description = $data['seo_description'];
        $seo->seo_keywords = $data['seo_keywors'];
        $seo->entity_id = (int) $data['entity_id'];

        $dataNew = $this->seoTranslate($data['tin-title'], $data['seo-title']);

        // Если указан путь - записываем его
        // Иначе - записываем новый сформированный специальной функцией путь.
        if ( $data['seo-path'] ) {
            $seo->path = $data['seo-path'];
        } else {
            $seo->path = $dataNew['path'];
        }

        $seo->seo_title = $dataNew['title'];

        $seo->save();

        return $seo;
    }

    /**
     * @param string $tin_title
     * @param string $seo_title
     * @return array
     */
    private function seoTranslate(string $tin_title, string $seo_title): array
    {
        $data = [];
        if ( iconv_strlen($seo_title) < 1 ) {
            $data['path'] = str2url($tin_title);
            $data['title'] = $tin_title;
        } else {
            $data['title'] = $seo_title;
            $data['path'] = str2url($seo_title);
        }

        return $data;
    }
}
