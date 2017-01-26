<?php

namespace App\Http\Controllers\User\Index;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function getIndex()
    {
        return view('user.index.main');
    }
}