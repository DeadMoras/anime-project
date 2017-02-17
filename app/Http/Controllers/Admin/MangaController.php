<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Seo;
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
