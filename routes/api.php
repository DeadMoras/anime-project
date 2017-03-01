<?php


// Auth events
Route::post('/auth', 'Api\AuthApi@postAuth');
Route::post('/register', 'Api\AuthApi@postRegister');

// Anime
Route::get('anime/get ', 'Api\Anime\AnimeApi@getAnime');
