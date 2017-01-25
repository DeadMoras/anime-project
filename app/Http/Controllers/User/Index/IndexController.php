<?php

namespace App\Http\Controllers\User\Index;

use App\Http\Controllers\Controller;
use App\UploadFiles\Google\GoogleUpload;
use App\UploadFiles\Mail\MailUpload;
use App\UploadFiles\UploadFiles;
use App\UploadFiles\Yandex\YandexUpload;

class IndexController extends Controller
{
    public function getIndex()
    {
        return view('user.index.main');
    }
}