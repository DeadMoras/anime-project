<?php

Route::get('anime/get ', 'Api\Anime\AnimeApi@getAnime');
Route::get('set/likes ', 'Api\Likes\LikeController@setLike');
