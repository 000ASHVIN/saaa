<?php

/***********************
 * Frontend Resource Centre
 ***********************/
Route::group(['prefix' => 'resource_centre', 'as' => 'resource_centre.',  'namespace' => 'ResourceCentre'], function() {
    Route::get('/', ['as' => 'home', 'uses' => 'FrontendResourceCentreController@index']);
    Route::post('search', ['as' => 'search', 'uses' => 'FrontendResourceCentreController@search']);

    Route::group(['prefix' => 'technical_faqs', 'as' => 'technical_faqs.','middleware' => 'auth'], function (){

        // Route::get('/', ['as' => 'index', 'uses' => 'TechnicalFaqController@index']);
        Route::get('/', ['as' => 'index', 'uses' => 'TechnicalFaqController@show']);
        Route::get('show/{id}', ['as' => 'show', 'uses' => 'TechnicalFaqController@show']);
        Route::get('general/{id}', ['as' => 'general_show', 'uses' => 'TechnicalFaqController@general_show']);
        Route::get('folder/{id}/solutions', ['as' => 'solutions', 'uses' => 'TechnicalFaqController@solutions']);
        Route::get('solution/{id}', ['as' => 'view_solutions', 'uses' => 'TechnicalFaqController@view_solutions']);
        Route::get('search', ['as' => 'search', 'uses' => 'TechnicalFaqController@search']);
    });

    Route::group(['prefix' => 'legislation', 'as' => 'legislation.','middleware' => 'auth'], function (){
        Route::get('/', ['as' => 'index', 'uses' => 'LegislationController@index']);
        Route::get('opinion', ['as' => 'opinion', 'uses' => 'LegislationController@opinion']);
        Route::get('show/{threadId}', ['as' => 'show', 'uses' => 'LegislationController@show']);
        Route::get('create', ['as' => 'create', 'uses' => 'LegislationController@create']);
        Route::post('create', ['as' => 'store', 'uses' => 'LegislationController@store']);
        Route::post('search', ['as' => 'search', 'uses' => 'LegislationController@search']);
    });

    Route::group(['prefix' => 'acts', 'as' => 'acts.','middleware' => 'auth'], function (){
        Route::get('show/{actId}', ['as' => 'show', 'uses' => 'ActController@show']);
        Route::get('single/{id}', ['as' => 'single_show', 'uses' => 'ActController@showAct']);
    });
});