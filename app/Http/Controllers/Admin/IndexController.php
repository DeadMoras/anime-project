<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaints;

class IndexController extends Controller
{
    public function getIndex()
    {
        $complaints = Complaints::all();

        return view('admin.index.main', compact('complaints'));
    }
}