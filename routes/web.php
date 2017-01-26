<?php

Route::group(['namespace' => 'User'], function() {
    // Auth Routers
    Route::get('/register', 'Auth\AuthController@getRegister');
    Route::post('/register', 'Auth\AuthController@postRegister');
    Route::post('/auth', 'Auth\AuthController@postAuth');

    // Activate account code
    Route::get('/activate/{code?}', 'Auth\ActivateController@activateAccount');

    Route::get('/', 'Index\IndexController@getIndex');
});

//image controller
Route::post('/save_image', 'ImageController@saveImage');

// Upload service
Route::get('/upload-service/token', '\App\UploadFiles\TokenService@getToken');
Route::post('/vk-save-video', '\App\UploadFiles\UploadDelegator@getUpload');

// Logout
Route::post('/logout', function() {
    \Auth::logout();
    return redirect('/');
});