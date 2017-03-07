<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AnimeSeries extends Model
{
    protected $table = 'anime_series';

    /**
     * @param array $data
     * @return bool
     */
    public function deleteSeries(array $data): bool
    {
        $deleted = [];

        foreach ($data as $key => $value) {
            if (1 == $value) {
                $deleted[] = $key;
            }
        }

        if (count($deleted)) {
            DB::table('anime_series')->whereIn('id', $deleted)->delete();
        }

        return true;
    }

    public function anime()
    {
        return $this->belongsTo('App\Models\Anime', 'entity_id');
    }
}
