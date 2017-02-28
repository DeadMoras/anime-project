<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EntityGenre;
use App\Models\Image;
use App\Models\Manga;
use App\Models\Seo;
use App\Models\Tom;
use App\Models\ToTom;
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
        $manga = DB::table('manga')
                ->select('manga.id', 'manga.name', 'manga.status', 'manga.visits', 'images.name as image_name')
                ->leftJoin('images', 'images.entity_id', '=', 'manga.id')
                ->paginate(15);

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
        (new Tom)->newTom($manga->id, $request->input('uploaded-tom_to--manga'));

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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $manga = DB::table('manga')
                ->select('manga.*', 'images.name as image_name', 'images.id as image_id',
                        'seo.seo_title', 'seo.id as seo_id', 'seo.seo_description', 'seo.seo_keywords', 'seo.path')
                ->leftJoin('images', 'images.entity_id', '=', 'manga.id')
                ->leftJoin('seo', 'seo.entity_id', '=', 'manga.id')
                ->where('manga.id', $id)
                ->first();

        $is_new = false;

        $genres = (new GenresController)->getGenres($id, 'manga');

        $toms = (new Tom)->getToms($id);

        return view('admin.manga.crup', compact('manga', 'is_new', 'genres', 'toms'));
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
        $manga = (new Manga)->newManga(true, $id);

        // Сохраняем том
        if ($request->input('uploaded-tom_to--manga')) {
            (new Tom)->newTom($id, $request->input('uploaded-tom_to--manga'));
        }

        // Сохраняем жанры
        if ($request->input('genres')) {
            (new EntityGenre)->updateEntity($request->input('genres'), $manga->id);
        }

        // Delete uploaded image
        if (0 != $request->input('delete-uploaded_image')) {
            $imageName = Image::select('name')
                    ->where('entity_id', $id)
                    ->first();

            $image = new Image;

            $image->deleteImage($request->input('delete-uploaded_image'), $imageName->name, 'manga');

            // image name
            $newNameImage = strOther($request->input('manga_name'), '') . '.' . explode('/', $request->input('image_mimeType'))[1];

            // Метод для изменения имени загруженной аватарки в базе данных
            $image->renameAvatar($newNameImage, $request->input('image_id'), true, $manga->id);

            // Метод для изменения имени загружнной аватрки в папке
            $image->renameAvatarDir(explode('/', $request->input('image_name'))[3], $newNameImage, 'manga');
        }

        // Айди тома с которым происходит работа
        $tomId = $request->input('uploaded-images_to--tom__id');
        // Сохраняем загруженные томы
        if ($request->input('uploaded_image_tom')) {
            (new ToTom)->saveImages($id, $tomId, $request->input('uploaded_image_tom'));
        }

        // Проверяем, нужно ли удалить какие-нибудь загруженные картинки к тому
        if ($request->input('delete-images_to--tom')) {
            (new ToTom)->deleteImages($id, $request->input('delete-images_to--tom'));
        }

        // Проверяем, нужно ли удалить какой-нибудь том
        if ($request->input('delete-tom')) {
            (new Tom)->deleteToms($id, $request->input('delete-tom'));
        }

        // Проверяем, нужно ли удалить жанры
        (new EntityGenre)->deleteGenres($id, $request->input('genres'));

        $seoData = [
                'bundle' => 'manga',
                'seo_description' => $request->input('seo_description'),
                'seo_title' => $request->input('seo_title'),
                'seo_keywords' => $request->input('seo_keywords'),
                'seo_path' => $request->input('seo_path'),
                'entity_id' => $id,
                'tin_title' => $request->input('manga_name')
        ];

        (new Seo)->newSeo($seoData, false, $request->input('seo_id'));

        if ($request->input('update') == '1') {
            return redirect('admin/manga/' . $id . '/edit');
        } elseif ($request->input('update_close') == '1') {
            return redirect('admin/manga');
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
