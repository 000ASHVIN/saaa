<?php

Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::post('checklogin', 'Auth\AuthController@checklogin');

Route::get('switch/start/{id}', 'ImpersonateController@user_switch_start');
Route::get('switch/stop', 'ImpersonateController@user_switch_stop');

/**
 * Peach Debit Order Routes
 */
Route::post('peach/otp', ['as' => 'peach.otp', 'uses' => 'PeachWebhooksController@otp']);
Route::post('peach/validate', ['as' => 'peach.validate', 'uses' => 'PeachWebhooksController@validateAccount']);
Route::get('peach/notify', ['as' => 'peach.notift', 'uses' => 'PeachWebhooksController@store']);
Route::post('peach/notify', ['as' => 'peach.notift_post', 'uses' => 'PeachWebhooksController@post_store']);

/***********************
 * Instant EFT Routes 
 ***********************/
Route::post('instant-eft', ['as' => 'instanteft.store', 'uses' => 'InstantEftController@store']);
Route::get('instant-eft/success', ['as' => 'instanteft.success', 'uses' => 'InstantEftController@success']);
Route::get('instant-eft/failed', ['as' => 'instanteft.failed', 'uses' => 'InstantEftController@failed']);
Route::get('instant-eft/cancelled', ['as' => 'instanteft.cancelled', 'uses' => 'InstantEftController@cancelled']);
Route::post('instant-eft/notify', ['as' => 'instanteft.notify', 'uses' => 'InstantEftController@notify']);
Route::post('instant-eft/webhook/notify', ['as' => 'instanteft.webhook.notify', 'uses' => 'InstantEftController@whnotify']);

/***********************
 * Registration Routes
 ***********************/
Route::get('auth/register{plan?}', 'Auth\AuthController@getRegister');
Route::post('auth/email/check', 'Auth\AuthController@checkMail');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::post('verify/email_id', 'Auth\AuthController@verify');

/***********************
 * Subscription Routes
 ***********************/
Route::get('subscriptions', ['as' => 'subscriptions.index', 'uses' => 'Subscriptions\SubscriptionController@index']);
Route::post('subscriptions', ['as' => 'subscriptions.store', 'uses' => 'Subscriptions\SubscriptionController@store']);

Route::get('subscriptions/2020/BlackFriday', ['as' => 'subscriptions.2020.deals', 'uses' => 'Subscriptions\SubscriptionUpgradeController@bf_deals']);
Route::get('subscriptions/2021/BlackFriday', ['as' => 'subscriptions.2020.deals', 'uses' => 'Subscriptions\SubscriptionUpgradeController@bf_deals']);
Route::get('subscriptions/2020/one-day-only', ['as' => 'subscriptions.2020.deals', 'uses' => 'Subscriptions\SubscriptionUpgradeController@corona_deals']);
Route::get('subscriptions/2019/One-Day-Only', ['as' => 'subscriptions.2019.one_day_only', 'uses' => 'Subscriptions\SubscriptionUpgradeController@oneDayOnly']); 
Route::get('subscriptions/2020/One-Day-Only', ['as' => 'subscriptions.2019.one_day_only', 'uses' => 'Subscriptions\SubscriptionUpgradeController@corona_deals']); 
Route::get('subscriptions/2021/One-Day-Only', ['as' => 'subscriptions.2019.one_day_offer', 'uses' => 'Subscriptions\SubscriptionUpgradeController@one_day_offer']); 
Route::get('subscriptions/2022/One-Day-Only', ['as' => 'subscriptions.2019.one_day_offer', 'uses' => 'Subscriptions\SubscriptionUpgradeController@one_day_offer']); 
Route::get('subscriptions/LastMinute', ['as' => 'subscriptions.last_minute', 'uses' => 'Subscriptions\SubscriptionUpgradeController@last_minute']);
Route::get('subscriptions/LastMinute-saiba', ['as' => 'subscriptions.last_minute_saiba', 'uses' => 'Subscriptions\SubscriptionUpgradeController@last_minute_saiba']);
Route::get('subscriptions/upgrade', ['as' => 'subscriptions.upgrade', 'uses' => 'Subscriptions\SubscriptionUpgradeController@index']);
Route::post('subscriptions/upgrade', ['as' => 'subscriptions.postUpgrade', 'uses' => 'Subscriptions\SubscriptionUpgradeController@store']);
Route::get('subscriptions/{package}', ['as' => 'subscriptions.2019.one_day_only.package', 'uses' => 'Subscriptions\SubscriptionUpgradeController@package']); 
Route::get('join', 'Subscriptions\SubscriptionController@index');
Route::get('subscriptions/plans', 'Subscriptions\SubscriptionController@getPlans');

/********************
 * CPD Subscription Routes for join, Cancellation, package changes
 ********************/
Route::group(['prefix' => 'subscriber_cpd', 'as' => 'subscriber_cpd.'], function (){
    Route::post('join_subscription', ['as' => 'join_subscription', 'uses' => 'ChangeCpSubscriptionController@join_subscription']);
    Route::post('change_subscription', ['as' => 'change_subscription', 'uses' => 'ChangeCpSubscriptionController@change_subscription']);
    Route::post('cancel_subscription', ['as' => 'cancel_subscription', 'uses' => 'ChangeCpSubscriptionController@cancel_subscription']);
});

/***********************
 * Professional Bodies Membership confirmation.
 ***********************/
Route::get('membership/{id}/confirm', ['as' => 'membership_confirm', 'uses' => 'ConfirmMembershipController@update']);
Route::get('membership/{id}/decline', ['as' => 'membership_decline', 'uses' => 'ConfirmMembershipController@decline']);
Route::get('membership/{id}/resend', ['as' => 'resend_membership_confirm', 'uses' => 'ConfirmMembershipController@resend']);

/***********************
 * Session Routes
 ***********************/
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', ['uses' => 'Auth\AuthController@postLogin']);
Route::get('auth/logout', ['as' => 'auth.logout', 'uses' => 'Auth\AuthController@getLogout']);

Route::get('test/{from}/{to}/{type}', ['as' => 'test', 'uses' => 'TestController@test']);

/***********************
 * Password Reset Routes
 ***********************/
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::get('/api/getMyCustomConfig', 'Api\GetConfigForJS@index');