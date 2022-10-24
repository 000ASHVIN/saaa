<?php

/**
 * PI Insurance
 */
Route::get('insurance', ['as' => 'insurance.index', 'uses' => 'InsuranceController@index']);
Route::post('insurance/user', ['as' => 'insurance.storeUser', 'uses' => 'InsuranceController@storeUser']);
Route::post('insurance/address', ['as' => 'insurance.storeAddress', 'uses' => 'InsuranceController@storeAddress']);
Route::post('insurance/assessment', ['as' => 'insurance.storeAssessment', 'uses' => 'InsuranceController@storeAssessment']);
Route::post('insurance/complete', ['as' => 'insurance.storeComplete', 'uses' => 'InsuranceController@storeComplete']);