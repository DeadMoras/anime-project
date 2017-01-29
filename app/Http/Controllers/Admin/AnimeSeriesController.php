<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnimeSeries;
use Illuminate\Http\Request;

class AnimeSeriesController extends Controller
{
    public function newSeries(string $link, int $animeId)
    {
        $anime = new AnimeSeries;
        $anime->link = $link;
        $anime->entity_id = $animeId;
        $anime->save();
    }
}