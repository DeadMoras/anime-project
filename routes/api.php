<?php

Route::group(
    ['namespace' => 'Api'], function () {

    // Auth events
    Route::group(
        ['namespace' => 'Auth'], function () {
        Route::post('/auth', 'Api@postAuth');
        Route::post('/register', 'Api@postRegister');
    });

    // Anime
    Route::group(
        ['namespace' => 'Anime'], function () {
        Route::get('anime/get', 'AnimeApi@getAnime');
        Route::get('anime/best', 'AnimeApi@getBestAnime');
        Route::get('anime/{title}', 'AnimeApi@getEachAnime');
    });

    // Manga
    Route::group(
        ['namespace' => 'Manga'], function () {
        Route::get('manga/statistic', 'MangaApi@getMangaStatistic');
    });

    // Comments
    Route::group(['namespace' => 'Comments'], function() {
        Route::post('comments/anime/get', 'Anime\CommentsApi@getComments');
        Route::post('comments/anime/add', 'Anime\CommentsApi@addComment');
    });

    // Likes
    Route::group(
        ['namespace' => 'Likes'], function () {
        Route::post('likes/set', 'LikesApi@setLike');
    });

    Route::group(
        ['namespace' => 'Users'], function () {
        Route::get('/user/profile/{id}/{userId?}', 'UserApi@getProfileUser');
        Route::post('/user/get', 'UserApi@getInfoUser');
        Route::post('/user/add-to-list', 'UserListApi@newToList');
    });
});

//image controller
Route::post('/save_image', 'ImageController@saveImage');
