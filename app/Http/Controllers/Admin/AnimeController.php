<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use App\Models\Image;
use App\Models\Seo;
use App\UploadFiles\UploadFiles;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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

        $anime = new Anime;
        $anime->name = $request->input('anime_name');
        $anime->status = $request->input('anime_status');
        $anime->year = $request->input('anime_year');
        $anime->age = $request->input('anime_age');
        $anime->same_entity_id = $request->input('sameAnime') ? $request->input('sameAnime') : null;
        $anime->save();

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

        (new Seo)->newSeo($seoData);

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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
