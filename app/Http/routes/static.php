<?php

/***********************
 * Static Routes
 ***********************/
Route::get('/', ['as' => 'home', 'uses' => 'Pages\PagesController@home']);
Route::post('/', ['as' => 'home', 'uses' => 'Pages\PagesController@home']);
Route::get('about', ['as' => 'about', 'uses' => 'Pages\AboutController@showAbout']);
Route::get('staff', ['as' => 'staff', 'uses' => 'Pages\AboutController@showStaff']);
Route::get('ourteam', ['as' => 'staff', 'uses' => 'Pages\AboutController@showStaff']);
Route::get('partners', ['as' => 'partners', 'uses' => 'Pages\AboutController@showPartners']);
Route::get('partners/draftworx', ['as' => 'partners/draftworx', 'uses' => 'Pages\AboutController@showDraftworx']);
Route::get('partners/saiba', ['as' => 'partners/saiba', 'uses' => 'Pages\AboutController@showSaiba']);
Route::get('cipc/test_drive', ['as' => 'cipc_test_dirve', 'uses' => 'Pages\TestDriveEventController@index']);

Route::get('privacy_policy', ['as' => 'privacy_policy', 'uses' => 'Pages\TermsController@privacy_policy']);
Route::get('terms_and_conditions', ['as' => 'terms_and_conditions', 'uses' => 'Pages\TermsController@terms_and_conditions']);
Route::get('contact', ['as' => 'contact', 'uses' => 'Pages\ContactController@create']);
Route::post('contact', ['as' => 'contact_store', 'uses' => 'Pages\ContactController@store']);

// Static Courses Pages
Route::group(['prefix' => 'courses', 'as' => 'courses.'], function (){
    Route::get('certificate_courses', ['as' => 'certificate_courses', 'uses' => 'Pages\CertificateController@index']);
    Route::get('ifrs_for_smes', ['as' => 'ifrs_for_smes', 'uses' => 'Pages\CertificateController@ifrs_for_smes']);
    Route::get('independent_review_engagements', ['as' => 'independent_review_engagements', 'uses' => 'Pages\CertificateController@independent_review_engagements']);
    Route::get('practising_licence_independent_review_engagements', ['as' => 'practising_licence_independent_review_engagements', 'uses' => 'Pages\CertificateController@practising_licence_independent_review_engagements']);
    Route::get('external_courses', ['as' => 'external_courses', 'uses' => 'Pages\CertificateController@external_courses']);
    Route::get('assessment_programme', ['as' => 'assessment_programme', 'uses' => 'Pages\CertificateController@assessment_programme']);
    Route::get('ifrs_learning_and_assessment_programme', ['as' => 'ifrs_learning_and_assessment_programme', 'uses' => 'Pages\CertificateController@ifrs_learning_and_assessment_programme']);
    Route::get('ifrs_for_smes_learning_and_assessment_programme', ['as' => 'ifrs_for_smes_learning_and_assessment_programme', 'uses' => 'Pages\CertificateController@ifrs_for_smes_learning_and_assessment_programme']);
    Route::get('isas_online_learning_and_assessment_programme', ['as' => 'isas_online_learning_and_assessment_programme', 'uses' => 'Pages\CertificateController@isas_online_learning_and_assessment_programme']);
});

/***********************
 * CPD View Routes
 ***********************/
Route::get('cpd', ['as' => 'cpd', 'uses' => 'Pages\AboutController@showCpd']);
Route::get('freewebinars', ['as' => 'freewebinars', 'uses' => 'Pages\AboutController@freewebinars']);
//Route::get('cpd/accounting_officer', ['as' => 'cpd.accounting_officer', 'uses' => 'Pages\AboutController@accounting_officer']);
//Route::get('cpd/build_your_own_package', ['as' => 'cpd.build_your_own_package', 'uses' => 'Pages\AboutController@build_your_own_package']);
//Route::get('cpd/independent_reviewer', ['as' => 'cpd.independent_reviewer', 'uses' => 'Pages\AboutController@independent_reviewer']);
//Route::get('cpd/accounting_only', ['as' => 'cpd.accounting_only', 'uses' => 'Pages\AboutController@accounting_only']);
//Route::get('cpd/tax_accountant', ['as' => 'cpd.tax_accountant', 'uses' => 'Pages\AboutController@tax_accountant']);

/***********************
 * Calendar Routes
 ***********************/
Route::get('calendar', ['as' => 'calendar', 'uses' => 'CalendarController@index']);

/***********************
 * Events Gallery
 ***********************/
Route::resource('gallery', 'GalleryController');

Route::get('unsubscribe/{email}', ['as' => 'unsubscribe.email', 'uses' => 'UnsubscribeController@unsubscribe']);
Route::post('unsubscribe/{email}/reason', ['as' => 'unsubscribe.reason', 'uses' => 'UnsubscribeController@addUnsubscribeReason']);

Route::get('resubscribe/{email}', ['as' => 'resubscribe.email', 'uses' => 'UnsubscribeController@resubscribe']);
Route::post('resubscribe/{email}/types', ['as' => 'resubscribe.type', 'uses' => 'UnsubscribeController@resubscribeType']);