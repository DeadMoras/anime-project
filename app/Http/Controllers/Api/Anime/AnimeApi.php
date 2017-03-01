<?php

namespace App\Http\Controllers\Api\Anime;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use Illuminate\Http\Request;

class AnimeApi extends Controller
{
    public function getAnime (Request $request) {
        $data = Anime::with('author')->take(20)->get();
        return response()->json($data);
    }
}
