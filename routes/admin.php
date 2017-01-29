<?php

Route::group(['namespace' => 'Admin'], function () {
    // index
    Route::get('/', 'IndexController@getIndex');

    // Search
    Route::post('/search', 'AdminSearch@search');

    // Complaints
    Route::resource('complaints', 'ComplaintsController');
    Route::post('complaints/update', 'ComplaintsController@indexUpdate');

    // Users
    Route::resource('/users', 'UsersController');
    Route::post('users/update', 'UsersController@indexUpdate');

    // Anime
    Route::resource('/anime', 'AnimeController');
    Route::post('anime/update', 'AnimeController@indexUpdate');
    // Same anime
    Route::post('/search/same_anime', 'AnimeController@sameAnimeSearch');
});