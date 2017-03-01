<?php

namespace App\Http\Controllers\User\Index;

class IndexController
{
    public function getIndex()
    {
        return view('user.index.main');
    }
}