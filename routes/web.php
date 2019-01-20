<?php
Route::get('', 'PostController@index')->name('index');
Route::post('store', 'PostController@store')->name('store');
Route::get('getPosts', 'PostController@getPosts')->name('getPosts');
Route::get('getPost', 'PostController@getPost')->name('getPost');
Route::get('deletePost', 'PostController@delete')->name('deletePost');