<?php

/***********************
 * Newsletter Routes
 ***********************/
Route::post('newsletter/subscribe', 'Newsletters\NewslettersController@store');
Route::get('newsletterSubscribers', ['as' => 'NewsletterSubscribers', 'uses' => 'Newsletters\NewslettersController@getSubscribers']);
Route::post('DestroyNewsletterSubscribers/{id}', ['as' => 'DestroyNewsletterSubscribers', 'uses' => 'Newsletters\NewslettersController@destroy']);