<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function saveImage(Request $request)
    {
        if ( $request->hasFile('image') ) {
            $result = (new Image)->uploadImage();
            return response()->json($result);
        } else {
            return response()->json('error');
        }
    }
}
