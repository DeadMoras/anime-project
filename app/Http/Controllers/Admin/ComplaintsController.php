<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaints;
use App\Models\UserInfo;
use Illuminate\Http\Request;

class ComplaintsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function indexUpdate(Request $request)
    {
        $_key = [];

        foreach ($request->input('options') as $key => $val) {
            if ($val == 1) {
                $_key[] = $key;
            }
        }

        (new Complaints)->multiOptions($_key);

        return redirect('/admin/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Complaints::findOrFail($id);

        $user = UserInfo::where('entity_id', $data->entity_id_user)->first();

        $post = null;

        if ( $data->bundle == 'anime.php' ) {
            $post = Anime::findOrFail($data->entity_id_post);
        } elseif ( $data->bundle == 'manga' ) {
            $post = Manga::findOrFail($data->entity_id_post);
        } elseif ( $data->bundle == 'comments' ) {
            $post = Comments::findOrFail($data->entity_id_post);
        }

        return view('admin.complaints.edit', compact('data', 'user', 'post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
