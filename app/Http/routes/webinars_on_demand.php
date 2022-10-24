<?php

Route::group(['prefix' => 'webinars_on_demand', 'as' => 'webinars_on_demand.'], function (){
    Route::get('/', ['as' => 'home', 'uses' => 'WebinarsOnDemandController@index']);
    Route::get('show/{slug}', ['as' => 'show', 'uses' => 'WebinarsOnDemandController@show']);
    Route::get('checkout/{slug}', ['as' => 'checkout', 'uses' => 'WebinarsOnDemandController@checkout']);
    Route::get('add-to-cart/{slug}', ['as' => 'add-to-cart', 'uses' => 'WebinarsOnDemandController@addToCart']);
    Route::post('checkout/{slug}/complete', ['as' => 'checkout.complete', 'uses' => 'WebinarsOnDemandController@post_checkout']);

    Route::post('/category', ['as' => 'category', 'uses' => 'WebinarsOnDemandController@category']);
    Route::post('/video-category', ['as' => 'video.category', 'uses' => 'WebinarsOnDemandController@videoCategory']);
    Route::post('/play', ['as' => 'play', 'uses' => 'WebinarsOnDemandController@webinars_on_demand_play']);

    
    /*
     * Search Functionality
     */
    Route::get('search/{api?}', ['as' => 'search', 'uses' => 'WebinarsOnDemandController@searchWebinars']);
    Route::post('search/{api?}', ['as' => 'search', 'uses' => 'WebinarsOnDemandController@search']);
    Route::get('/{type}', ['as' => 'webinar_type', 'uses' => 'WebinarsOnDemandController@webinar_type']);
});