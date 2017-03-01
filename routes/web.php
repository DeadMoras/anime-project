<?php

// Activate
Route::get('/activate/{code?}', 'ActivateController@activateAccount');

Route::get('/', 'User\Index\IndexController@getIndex');

// Upload service
Route::get('/upload-service/token', '\App\UploadFiles\TokenService@getToken');
Route::post('/vk-save-video', '\App\UploadFiles\UploadDelegator@getUpload');
Route::post('/vk-save-image', '\App\UploadFiles\UploadDelegator@uploadImage');

// Logout
Route::post(
    '/logout', function () {
    \Auth::logout();

    return redirect('/');
});