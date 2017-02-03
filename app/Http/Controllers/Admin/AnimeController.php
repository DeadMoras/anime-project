<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use App\Models\AnimeSeries;
use App\Models\Image;
use App\Models\Seo;
use App\UploadFiles\UploadFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $anime = DB::table('anime')
                ->select('anime.id', 'anime.name', 'anime.status', 'anime.visits', 'images.name as image_name')
                ->leftJoin('images', 'images.entity_id', '=', 'anime.id')
                ->paginate(15);

        return view('admin.anime.index', compact('anime'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $anime = new \stdClass();

        $is_new = true;

        return view('admin.anime.crup', compact('is_new', 'anime'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $this->validate($request, [
//                'anime_name' => 'required',
//                'anime_status' => 'required',
//                'anime_year' => 'required',
//                'anime_age' => 'required',
//                'image_id' => 'required'
//        ]);

        $anime = (new Anime)->newAnime();

        $newVideo = null;

        if ($request->input('uploaded-video')) {
            $newVideo = $request->input('uploaded-video');
        } elseif ($request->input('anime-new_link')) {
            $newVideo = $request->input('anime-new_link');
        }

        // Anime series
        (new AnimeSeriesController)->newSeries($newVideo, $anime->id);

        // image name
        $newNameImage = strOther($request->input('anime_name'), '') . '.' . explode('/', $request->input('image_mimeType'))[1];

        // Метод для изменения имени загруженной аватарки в базе данных
        (new Image)->renameAvatar($newNameImage, $request->input('image_id'), true, $anime->id);

        // Метод для изменения имени загружнной аватрки в папке
        (new Image)->renameAvatarDir($request->input('image_name'), $newNameImage, 'anime');

        $seoData = [
                'bundle' => 'anime',
                'seo_description' => $request->input('seo_description'),
                'seo_title' => $request->input('seo_title'),
                'seo_keywords' => $request->input('seo_keywords'),
                'seo_path' => $request->input('seo_path'),
                'entity_id' => $anime->id,
                'tin_title' => $anime->name
        ];

        (new Seo)->newSeo($seoData, true);

        if ($request->input('update') == '1') {
            return redirect('admin/anime/' . $anime->id . '/edit');
        } elseif ($request->input('update_close') == '1') {
            return redirect('admin/anime');
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
        $anime = DB::table('anime')
                ->select('anime.*', 'images.name as image_name', 'images.id as image_id',
                        'seo.seo_title', 'seo.id as seo_id', 'seo.seo_description', 'seo.seo_keywords', 'seo.path')
                ->leftJoin('images', 'images.entity_id', '=', 'anime.id')
                ->leftJoin('seo', 'seo.entity_id', '=', 'anime.id')
                ->where('anime.id', $id)
                ->first();

        $sameAnime = null;

        if (null !== $anime->same_entity_id) {
            $sameAnime = DB::table('anime')
                    ->select('anime.name', 'anime.id')
                    ->where('anime.id', $anime->same_entity_id)
                    ->first();
        }

        $animeSeries = DB::table('anime_series')
                ->select('id', 'link')
                ->where('anime_series.entity_id', $id)
                ->get();

        $is_new = false;

        return view('admin.anime.crup', compact('is_new', 'anime', 'sameAnime', 'animeSeries'));
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
        // Anime update
        $anime = (new Anime)->newAnime($id, false);

        $newVideo = null;

        if ($request->input('uploaded-video')) {
            $newVideo = $request->input('uploaded-video');
        } elseif ($request->input('anime-new_link')) {
            $newVideo = $request->input('anime-new_link');
        }

        // Anime series
        if (null != $newVideo) {
            (new AnimeSeriesController)->newSeries($newVideo, $id);
        }

        // Delete series
        (new AnimeSeries)->deleteSeries($request->input('delete-series'));

        // Delete uploaded image
        if ($request->input('delete-uploaded_image')) {
            $imageName = Image::select('name')
                    ->where('entity_id', $id)
                    ->first();

            $image = new Image;

            $image->deleteImage($request->input('delete-uploaded_image'), $imageName->name, 'anime');

            $newNameImage = strOther($request->input('anime_name'), '') . '.' . explode('/', $request->input('image_mimeType'))[1];

//             Метод для изменения имени загруженной аватарки в базе данных
            $image->renameAvatar($newNameImage, $request->input('image_id'), true, $anime->id);

//             Метод для изменения имени загружнной аватрки в папке
            $image->renameAvatarDir($request->input('image_name'), $newNameImage, 'anime');
        }

        $seoData = [
                'bundle' => 'anime',
                'seo_description' => $request->input('seo_description'),
                'seo_title' => $request->input('seo_title'),
                'seo_keywords' => $request->input('seo_keywords'),
                'seo_path' => $request->input('seo_path'),
                'entity_id' => $id,
                'tin_title' => $anime->name
        ];

        (new Seo)->newSeo($seoData, false, $request->input('seo_id'));

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
