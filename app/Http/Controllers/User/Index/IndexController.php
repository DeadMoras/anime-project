<?php

namespace App\Http\Controllers\User\Index;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function getIndex()
    {
        return view('user.index.main');
    }

    public function getStatisticInfo()
    {
        $data = [];

        $data['users'] = DB::table('user_info')
                ->select('user_info.login', 'user_info.id', 'images.name')
                ->leftJoin('images', 'images.entity_id', '=', 'user_info.entity_id')
                ->take(5)
                ->get();

        $data['manga'] = DB::table('manga')
                ->select('manga.name', 'manga.id', 'images.name as image')
                ->leftJoin('images', 'images.entity_id', '=', 'manga.id')
                ->where('images.bundle', 'manga')
                ->take(5)
                ->get();

        return response()->json($data, 200);
    }
}