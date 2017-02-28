<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaints;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSearch extends Controller
{
    // В этом свойстве будут хранится все данные, которые будут отправленны в ответы
    private $data;

    // Название таблицы. Так же, служит названием метода
    private $table;

    // Слова для поиска
    private $text;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * Метод принимает два параметра: текст поиска и параметр (таблица для поиска), а затем делегирует вызов другого метода
     */
    public function search(Request $request)
    {
        $this->table = $request->input('bundle');
        $this->text = $request->input('search');

        return call_user_func([__NAMESPACE__ . '\AdminSearch', $this->table]);
    }

    /**
     *
     */
    private function complain()
    {
        $data = Complaints::where('id', 'LIKE', '%' . $this->text . '%')
                          ->orWhere('entity_id_post', 'LIKE', '%' . $this->text . '%')
                          ->orWhere('email', 'LIKE', '%' . $this->text . '%')
                          ->orWhere('status', 'LIKE', '%' . $this->text . '%')
                          ->orWhere('bundle', 'LIKE', '%' . $this->text . '%')
                          ->get();

        $this->data = $data;

        return $this->responseFromSearch();
    }

    /**
     *
     */
    private function anime()
    {
        $data = DB::table('anime')
                ->select('anime.id', 'anime.name', 'anime.status', 'anime.visits', 'images.name as image_name')
                ->leftJoin('images', 'images.entity_id', '=', 'anime.id')
                ->where('anime.id', 'LIKE', '%' . $this->text . '%')
                ->orWhere('anime.name', 'LIKE', '%' . $this->text . '%')
                ->orWhere('anime.status', 'LIKE', '%' . $this->text . '%')
                ->get();

        $this->data = $data;

        return $this->responseFromSearch();
    }

    /**
     *
     */
    private function users()
    {
        $data = DB::table('users')
                  ->select('user_info.login', 'users.id', 'users.role', 'users.confirmed', 'images.name')
                  ->leftJoin('user_info', 'user_info.entity_id', '=', 'users.id')
                  ->leftJoin('images', 'images.entity_id', '=', 'users.id')
                  ->where('users.id', 'LIKE', '%' . $this->text . '%')
                  ->orWhere('users.role', 'LIKE', '%' . $this->text . '%')
                  ->orWhere('users.confirmed', 'LIKE', '%' . $this->text . '%')
                  ->orWhere('user_info.login', 'LIKE', '%' . $this->text . '%')
                  ->get();

        $this->data = $data;

        return $this->responseFromSearch();
    }

    /**
     *
     */
    private function manga()
    {
        $data = DB::table('manga')
                ->select('manga.id', 'manga.name', 'manga.status', 'manga.visits', 'images.name as image_name')
                ->leftJoin('images', 'images.entity_id', '=', 'manga.id')
                ->where('manga.id', 'LIKE', '%' . $this->text . '%')
                ->orWhere('manga.name', 'LIKE', '%' . $this->text . '%')
                ->orWhere('manga.status', 'LIKE', '%' . $this->text . '%')
                ->get();

        $this->data = $data;

        return $this->responseFromSearch();
    }

    /**
     * Метод который возвращает клиенту данные
     * Позже сделать перебор и привести данные в порядок
     */
    private function responseFromSearch()
    {
        return response()->json(['data' => $this->data]);
    }
}
