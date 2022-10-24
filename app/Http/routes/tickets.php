<?php

Route::group(['prefix' => 'support_tickets', 'as' => 'support_ticket.','middleware' => 'auth'], function () {
    Route::get('create', ['as' => 'create', 'uses' => 'SupportTicketController@create']);
    Route::post('store', ['as' => 'store', 'uses' => 'SupportTicketController@store']);
    Route::post('update', ['as' => 'update', 'uses' => 'SupportTicketController@update']);
    Route::get('thread/{id}', ['as' => 'show', 'uses' => 'SupportTicketController@show']);
    Route::post('reply/{id}', ['as' => 'reply', 'uses' => 'SupportTicketController@reply']);
});
Route::post('support_tickets_popup/store', ['as' => 'support_tickets_popup.store', 'uses' => 'SupportTicketController@store']);

Route::group(['prefix' => 'threads', 'as' => 'thread.'], function () {
    Route::post('store/{thread_id}', ['as' => 'store', 'uses' => 'SupportTicketReplyController@store']);
    Route::post('ticket/{id}/destroy', ['as' => 'destroy', 'uses' => 'SupportTicketReplyController@destroy']);
});