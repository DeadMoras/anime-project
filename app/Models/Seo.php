<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $table = 'seo';

    public $timestamps = false;

    /**
     * @param array $data
     * @param bool $new
     * @param int $id
     * @return Mseo
     *
     * Метод для создания новой записи в базе данных
     */
    public function newSeo(array $data, bool $new = true, int $id = 0)
    {
        $seo = null;

        if ( true == $new ) {
            $seo = new Seo;
        } elseif ( false == $new ) {
            $seo = Seo::findOrFail($id);
        }

        $seo->bundle = $data['bundle'];
        $seo->seo_description = $data['seo_description'];
        $seo->seo_keywords = $data['seo_keywords'];
        $seo->entity_id = (int) $data['entity_id'];

        $dataNew = $this->seoTranslate($data['tin_title'], $data['seo_title']);

        // Если указан путь - записываем его
        // Иначе - записываем новый сформированный специальной функцией путь.
        if ( $data['seo_path'] ) {
            $seo->path = $data['seo_path'];
        } else {
            $seo->path = $dataNew['path'];
        }

        $seo->seo_title = $dataNew['title'];

        $seo->save();

        return $seo;
    }

    /**
     * @param string $tinTitle
     * @param string $seoTitle
     * @return array
     */
    private function seoTranslate(string $tinTitle, string $seoTitle): array
    {
        $data = [];
        if ( iconv_strlen($seoTitle) < 1 ) {
            $data['path'] = strOther($tinTitle, 'seo');
            $data['title'] = $tinTitle;
        } else {
            $data['title'] = $seoTitle;
            $data['path'] = strOther($seoTitle, 'seo');
        }

        return $data;
    }
}
