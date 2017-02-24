<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EntityGenre;
use App\Models\Image;
use App\Models\Manga;
use App\Models\Seo;
use App\Models\Tom;
use App\UploadFiles\UploadFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MangaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $manga = DB::table('manga');

        return view('admin.manga.index', compact('manga'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $manga = new \stdClass();

        $is_new = true;

        return view('admin.manga.crup', compact('is_new', 'manga'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Записываем данные о манге
        $manga = (new Manga)->newManga();

        // Сохраняем том
        (new Tom)->newTom($manga->id, $request->input('uploaded_image_tom'));

        // Сохраняем жанры
        (new EntityGenre)->updateEntity($request->input('genres'), $manga->id);

        // image name
        $newNameImage = strOther($request->input('manga_name'), '') . '.' . explode('/', $request->input('image_mimeType'))[1];

        // Метод для изменения имени загруженной аватарки в базе данных
        (new Image)->renameAvatar($newNameImage, $request->input('image_id'), true, $manga->id);

        // Метод для изменения имени загружнной аватрки в папке
        (new Image)->renameAvatarDir(explode('/', $request->input('image_name'))[3], $newNameImage, 'manga');

        $seoData = [
                'bundle' => 'manga',
                'seo_description' => $request->input('seo_description'),
                'seo_title' => $request->input('seo_title'),
                'seo_keywords' => $request->input('seo_keywords'),
                'seo_path' => $request->input('seo_path'),
                'entity_id' => $manga->id,
                'tin_title' => $manga->name
        ];

        (new Seo)->newSeo($seoData, true);

        if ($request->input('update') == '1') {
            return redirect('admin/manga/' . $manga->id . '/edit');
        } elseif ($request->input('update_close') == '1') {
            return redirect('admin/manga');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**+
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->input('update') == '1') {
            return redirect('admin/anime/' . $id . '/edit');
        } elseif ($request->input('update_close') == '1') {
            return redirect('admin/anime');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function sameAnimeSearch(Request $request)
    {
        $text = $request->input('text');

        $data = Anime::where('name', 'LIKE', '%' . $text . '%')
                ->get();

        if ($data) {
            return response()->json(['data' => $data]);
        } else {
            return response()->json(['error' => 'Nothing in database']);
        }
    }
}
