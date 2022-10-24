<?php

Route::group(['prefix' => 'courses', 'as' => 'courses.'], function (){
    Route::get('/', ['as' => 'index', 'uses' => 'Course\CourseController@index']);
    Route::get('show/{reference}', ['as' => 'show', 'uses' => 'Course\CourseController@show']);
    Route::get('enroll/{reference}', ['as' => 'enroll', 'uses' => 'Course\CourseController@enroll']);
    Route::post('checkout/{reference}/complete', ['as' => 'checkout.complete', 'uses' => 'Course\CourseController@post_checkout']);
    Route::post('enroll/checkout/validate', ['as' => 'checkout.validate', 'uses' => 'Course\CourseController@validateField']);

    Route::post('search', ['as' => 'search', 'uses' => 'Course\CourseController@search']);
    Route::get('search', ['as' => 'search_get', 'uses' => 'Course\CourseController@search_get']);
    Route::post('course_process', ['as' => 'course_process', 'uses' => 'Course\CourseController@course_process']);

    Route::post('talk_to_human', ['as' => 'talk_to_human', 'uses' => 'Course\CourseController@talk_to_human']);

    Route::get('download_brochure/{course_id}', ['as' => 'download_brochure', 'uses' => 'Course\CourseController@download_brochure']);
    Route::get('/fund-a-learner', ['as' => 'fund.learner', 'uses' => 'Course\CourseController@fund_learner']);
});
