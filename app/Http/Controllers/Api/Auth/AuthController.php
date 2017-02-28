<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\TokenService\Components\Token;
use App\TokenService\TokenService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function postAuth(Request $request)
    {
        return (new TokenService(new Token))->getAuth($request->input('data'));
    }
}