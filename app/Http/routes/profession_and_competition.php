<?php

/***********************
 * Profession Routes
 ***********************/
Route::get('profession/{slug}', ['as' => 'profession.show', 'uses' => 'ProfessionController@show']);
Route::get('request/practice/{slug}/information', ['as' => 'request_practice_information', 'uses' => 'ProfessionController@request_practice_information']);
Route::post('request/practice/{slug}/information', ['as' => 'request_practice_information', 'uses' => 'ProfessionController@send_request_practice_information']);
Route::get('subscription_plans', ['as' => 'profession.plans_and_pricing', 'uses' => 'ProfessionController@plans_and_pricing']);

/***********************
 * Compitition Routes
 ***********************/
Route::get('competitions', ['as' => 'competitions.index', 'uses' => 'CompititionController@index']);