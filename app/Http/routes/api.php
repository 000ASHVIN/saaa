<?php

/***********************
 * API Routes
 ***********************/
Route::group(['prefix' => 'api', 'as' => 'api.'], function () {
    Route::get('users/me', ['as' => 'currentUser', 'uses' => 'Auth\AuthController@getCurrentUser']);
    Route::get('events/{id}', ['as' => 'events.show', 'uses' => 'Events\EventsController@getEvent']);
    Route::get('registration/email/{email}', ['as' => 'registration.email.check', 'uses' => 'Auth\AuthController@checkMail']);
    Route::get('subscriptions/plans', ['as' => 'get_plans', 'uses' => 'Subscriptions\SubscriptionController@getPlans']);
    Route::get('subscriptions/coupon/{code}', ['as' => 'check_coupon', 'uses' => 'CouponsController@checkCoupon']);
});

/***********************
 * Events API Routes
 ***********************/
Route::get('events/api/all', ['as' => 'api_all_events', 'uses' => 'EventsApiController@all_events']);
Route::get('events/api/{slug}', ['as' => 'api_all_events_show', 'uses' => 'EventsApiController@show_event']);



// Get all plan api
Route::group(['prefix' => 'api', 'as' => 'api.'], function () {
    Route::get('plan-list', ['as' => 'getplanlist', 'uses' => 'Api\ApiController@getPlanList']);
    Route::get('event-list', ['as' => 'geteventlist', 'uses' => 'Api\ApiController@getEventList']);
    
    Route::get('event/{id}', ['as' => 'getEventById', 'uses' => 'Api\ApiController@getEventById']);
    Route::get('event/{reference_id}/reference_id', ['as' => 'getEventByName', 'uses' => 'Api\ApiController@getEventByReferenceId']);
    Route::get('event/{name}/name', ['as' => 'getEventByName', 'uses' => 'Api\ApiController@getEventByName']);

    Route::get('event/{name}/sync/{reference_id}', ['as' => 'syncEvent', 'uses' => 'Api\ApiController@syncEvent']);
    Route::get('event/async/{reference_id}', ['as' => 'asyncEvent', 'uses' => 'Api\ApiController@asyncEvent']);
});