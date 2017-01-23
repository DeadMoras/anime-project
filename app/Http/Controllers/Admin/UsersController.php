<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')
                   ->select('user_info.login', 'users.id', 'users.role', 'users.confirmed', 'images.name')
                   ->leftJoin('user_info', 'user_info.entity_id', '=', 'users.id')
                   ->leftJoin('images', 'images.entity_id', '=', 'users.id')
                   ->paginate(15);

        return view('admin.user.index', compact('users'));
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

        foreach ( $request->input('options') as $key => $val ) {
            if ( $val == 1 ) {
                $_key[] = $key;
            }
        }

        (new User)->multiOptions($_key);

        return redirect('/admin/users');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd('store');
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
        $user = DB::table('users')
                  ->select('users.email', 'users.role', 'users.confirmed', 'user_info.*', 'images.name', 'images.id as image_id')
                  ->leftJoin('user_info', 'user_info.entity_id', '=', 'users.id')
                  ->leftJoin('images', 'images.entity_id', '=', 'users.id')
                  ->where('users.id', $id)
                  ->first();

        return view('admin.user.edit', compact('user'));
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
        if ( $request->input('close') == '1' ) {
            return redirect('admin/users');
        }

        $user_info = [
                'login' => $request->input('user_login'),
                'vk' => $request->input('user_vk', null),
                'twitter' => $request->input('user_twitter', null),
                'facebook' => $request->input('user_facebook', null),
                'skype' => $request->input('user_skype', null),
                'sex' => $request->input('user_sex')
        ];

        (new UserInfo)->updateInfo((array) $user_info, (int) $id);

        $user = [
                'email' => $request->input('user_email'),
                'confirmed' => $request->input('user_confirmed'),
                'role' => $request->input('user_role')
        ];

        (new User)->updateInfo((array) $user, (int) $id);

        if ( $request->input('user_image_delete') == '1' ) {
            $id = Image::select('id', 'name', 'mimetype')
                       ->where('entity_id', $id)
                       ->first();

            (new Image)->deleteImage((int) $id->id, (string) $id->name . $id->mimetype[1]);
        }

        if ( $request->input('update') == '1' ) {
            return redirect('admin/users/' . $id . '/edit');
        } elseif ( $request->input('update_close') == '1' ) {
            return redirect('admin/users');
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
}
