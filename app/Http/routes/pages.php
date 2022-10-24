<?php

Route::get('pi_users', ['as' => 'pi_users', 'uses' => 'TestController@pi_users']);
Route::get('subscriptions/2018/saiba_member', ['as' => 'saiba.cpd.index', 'uses' => 'saibaPagesController@index']);
Route::get('subscriptions/2018/tax_practitioner', ['as' => 'saiba.cpd.index', 'uses' => 'saibaPagesController@tax_practitioner']);
Route::get('cancel/cpd/confirm', ['as' => 'cancel_cpd', 'uses' => 'TestController@cancel_cpd']);
Route::post('cancel/cpd/confirm', ['as' => 'post_cancel_cpd', 'uses' => 'TestController@post_cancel_cpd']);
Route::get('professional_indemnity', ['as' => 'professional_indemnity', 'uses' => 'TestController@pi_insurance']);

/***********************
 * Presenter Routes
 ***********************/
Route::get('presenters', ['as' => 'presenters', 'uses' => 'PresenterController@index']);
Route::get('presenters/show/{id}', ['as' => 'presenters.show', 'uses' => 'PresenterController@show']);

/***********************
 * FAQ Routes
 ***********************/
Route::get('faq', ['as' => 'faq', 'uses' => 'FaqController@index']);
// Route::post('faq_question', ['as' => 'faq_help', 'uses' => 'FaqController@help']);
Route::group(['prefix' => 'faq', 'as' => 'faq.'], function() {
    Route::group(['prefix' => 'general_faqs', 'as' => 'general_faqs.'], function (){
        Route::get('folder/{id}/solutions', ['as' => 'solutions', 'uses' => 'FaqController@solutions']);
        Route::get('solution/{id}', ['as' => 'view_solutions', 'uses' => 'FaqController@view_solutions']);
        Route::get('search', ['as' => 'search', 'uses' => 'FaqController@search']);
    });
});

/***********************
 * Careers Routes
 ***********************/
Route::get('careers', ['as' => 'careers', 'uses' => 'CareersController@index']);
Route::get('careers/show/{slug}', ['as' => 'careers.show', 'uses' => 'CareersController@show']);

/***********************
 * CPD Routes
 ***********************/
Route::resource('cpds', 'Dashboard\CpdsController');

Route::get('test', ['as' => 'new_problem', 'uses' =>'TestController@test'] );
Route::post('problem/occurred', ['as' => 'new_problem', 'uses' =>'ProblemController@store'] );
Route::post('filter_events', ['as' => 'frontend.events.filter_events', 'uses' => 'Events\EventsController@search_events']);
Route::post('information_for/{event}', ['as' => 'information.request', 'uses' => 'Events\ContactEventController@store']);
Route::post('information_for_subscription', ['as' => 'information_for_subscription', 'uses' => 'Subscriptions\SubscriptionUpgradeController@need_help_with_subscription']);


Route::post('help', ['as' => 'help', 'uses' => 'Subscriptions\SubscriptionUpgradeController@help']);

/*
 * Sponsors Page
 */

Route::get('sponsors', ['as' => 'sponsors.index', 'uses' => 'SponsorController@index']);
Route::post('sponsors/store', ['as' => 'sponsors.store', 'uses' => 'SponsorController@store']);


/*
 * Rewards Controller
 */
route::get('rewards', ['as' => 'rewards.index', 'uses' => 'Rewards\RewardsController@index']);
route::get('rewards/show/{slug}', ['as' => 'rewards.show', 'uses' => 'Rewards\RewardsController@show']);  
route::get('draftworx', ['as' => 'rewards.draftworx', 'uses' => 'Rewards\RewardsController@draftworx']);
route::get('bluestar', ['as' => 'rewards.bluestar', 'uses' => 'Rewards\RewardsController@bluestar']);
route::get('acts', ['as' => 'rewards.acts', 'uses' => 'Rewards\RewardsController@acts']);
route::get('quickbooks', ['as' => 'rewards.quickbooks', 'uses' => 'Rewards\RewardsController@quickbooks']);
route::get('saiba', ['as' => 'rewards.saiba', 'uses' => 'Rewards\RewardsController@saiba']);
route::get('the-tax-shop', ['as' => 'rewards.taxshop', 'uses' => 'Rewards\RewardsController@taxshop']);
route::post('save_entry_for/{reward}', ['as' => 'rewards.store', 'uses' => 'Rewards\RewardsController@store']);
route::get('draftworx_applications', ['as' => 'rewards.draftworx_draftworx_applications', 'uses' => 'Rewards\RewardsController@draftworx_draftworx_applications']);
route::post('store_draftworx', ['as' => 'rewards.draftworx_store', 'uses' => 'Rewards\RewardsController@draftworx_store']);

route::get('EdNVest', ['as' => 'rewards.EdNVest', 'uses' => 'Rewards\RewardsController@EdNVest']);
route::get('InfoDocs', ['as' => 'rewards.InfoDocs', 'uses' => 'Rewards\RewardsController@InfoDocs']);
/*
 * QuestionaireController
 */
Route::resource('questionnaire', 'Questionaire\QuestionaireController');
Route::get('api/get-state-list','Location\LocationController@getStateList');
Route::get('api/get-city-list','Location\LocationController@getCityList');

Route::group(['prefix' => 'news', 'as' => 'news.'], function () {
    Route::get('/{category?}', ['as' => 'index', 'uses' => 'Blog\PostController@index']);
    Route::get('read/{post}', ['as' => 'show', 'uses' => 'Blog\PostController@show']);
    Route::post('search/{category?}', ['as' => 'search', 'uses' => 'Blog\PostController@search']);
    Route::post('{post}/comment/', ['as' => 'comment.store', 'uses' => 'Blog\CommentController@store']);
});

Route::get('wod', ['as' => 'wod_index', 'uses' => 'Pages\PagesController@wod']);
Route::get('donate', ['as' => 'donate', 'uses' => 'Pages\PagesController@donate']);
Route::post('donate/save', ['as' => 'donate.save', 'uses' => 'Pages\PagesController@donateSave']);
Route::get('BlackFriday', ['as' => 'bf', 'uses' => 'Pages\PagesController@bf']);
Route::get('BlackFriday/Draftworx', ['as' => 'bf.draftWorx', 'uses' => 'Pages\PagesController@draftWorx']);