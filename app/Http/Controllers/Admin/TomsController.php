<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ToTom;
use Illuminate\Http\Request;

class TomsController extends Controller
{
    public function getImages(Request $request)
    {
        $images = (new ToTom)->getImages($request->input('tomId'));

        if ( count($images) ) {
            return response()->json(['success' => $images]);
        } else {
            return response()->json(['error' => 'Nothing']);
        }
    }
}