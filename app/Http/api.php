<?php

Route::post('login', ['as' => 'login', 'uses' => 'AuthController@login']);
Route::post('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
Route::post('password', ['as' => 'password', 'uses' => 'AuthController@postEmail']);

Route::get('events', ['as' => 'events.index', 'uses' => 'EventsController@index']);

Route::group(['middleware' => 'jwt.auth'], function() {
	Route::get('user', ['as' => 'user', 'uses' => 'AuthController@user']);
	Route::get('user/webinars', ['as' => 'user.webinars.index', 'uses' => 'WebinarsController@myWebinars']);	
	Route::get('user/categories', ['as' => 'user.webinars.categories', 'uses' => 'WebinarsController@categories']);
	Route::get('webinars', ['as' => 'webinars.index', 'uses' => 'WebinarsController@index']);
});