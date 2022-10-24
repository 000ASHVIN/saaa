<?php

/***********************
 * Invoice Routes
 ***********************/
Route::get('invoices/view/{id}', ['as' => 'invoices.show', 'uses' => 'InvoicesController@show', 'middleware' => 'auth']);
Route::get('invoices/settle/{id}', ['as' => 'invoices.settle', 'uses' => 'InvoicesController@getSettle', 'middleware' => 'auth']);
Route::post('invoices/settle/{id}', ['as' => 'invoices.settle', 'uses' => 'InvoicesController@postSettle', 'middleware' => 'auth']);

/**********************
 * Credit Memo Routes
 **********************/
Route::get('credit_memo/view/{invoice}/cn/{id}', ['as' => 'cn.view', 'uses' => 'CreditMemoController@show']);

Route::resource('order', 'InvoiceOrderController');
Route::post('shipping/{shippingInformationId}/update', ['as' => 'admin.shipping.update', 'uses' => 'Admin\OrdersController@updateShippingInformation']);
Route::get('invoice/order/view/{id}', ['as' => 'order.show', 'uses' => 'InvoiceOrderController@show', 'middleware' => 'auth']);
Route::get('invoice/order/settle/{id}', ['as' => 'order.settle', 'uses' => 'InvoiceOrderController@getSettle', 'middleware' => 'auth']);
Route::post('invoice/order/cancel/{id}', ['as' => 'order.cancel', 'uses' => 'InvoiceOrderController@cancel', 'middleware' => 'auth']);
Route::post('invoice/order/settle/{id}', ['as' => 'order.settle', 'uses' => 'InvoiceOrderController@postSettle', 'middleware' => 'auth']);