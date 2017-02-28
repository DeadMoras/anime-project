<?php

Route::post('/api/auth', 'Api\Auth\AuthController@postAuth');

Route::group(['namespace' => 'User'], function() {
    // Auth Routers
//    Route::get('/register', 'Auth\AuthController@getRegister');
    Route::post('/register', 'Auth\AuthController@postRegister');

    // Activate account code
    Route::get('/activate/{code?}', 'Auth\ActivateController@activateAccount');

    // Index
    Route::get('/', 'Index\IndexController@getIndex');

    // Statictic for vue
    Route::get('/index-statistic', 'Index\IndexController@getStatisticInfo');

    // Anime for vue
    Route::get('/get-anime', 'Anime\AnimeController@getAnime');
});

//image controller
Route::post('/save_image', 'ImageController@saveImage');

// Upload service
Route::get('/upload-service/token', '\App\UploadFiles\TokenService@getToken');
Route::post('/vk-save-video', '\App\UploadFiles\UploadDelegator@getUpload');
Route::post('/vk-save-image', '\App\UploadFiles\UploadDelegator@uploadImage');

// Logout
Route::post('/logout', function() {
    \Auth::logout();
    return redirect('/');
});