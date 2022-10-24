<?php

/***********************
 * Events Routes
 ***********************/
Route::get('events/past', 'Events\EventsController@past');
Route::resource('events', 'Events\EventsController');

Route::group(['prefix' => 'events', 'as' => 'events.', 'middleware' => 'auth'], function () {
    Route::get('{slug}/register', ['as' => 'register', 'uses' => 'Events\EventsController@getRegister']);
    Route::post('{slug}/register', ['as' => 'register', 'uses' => 'Events\EventsController@postRegister']);
});

Route::post('check/{event}', ['as' => 'check_coupon', 'uses' => 'CouponController@check']);