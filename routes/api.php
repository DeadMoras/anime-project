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
    });

    // Manga
    Route::group(
        ['namespace' => 'Manga'], function () {
        Route::get('manga/statistic', 'MangaApi@getMangaStatistic');
    });

    // Likes
    Route::group(
        ['namespace' => 'Likes'], function () {
        Route::get('likes/set', 'Likes\LikesApi@setLike');
    });
});

//image controller
Route::post('/save_image', 'ImageController@saveImage');
