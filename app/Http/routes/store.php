<?php

/***********************
 * Online Store
 ***********************/
Route::group(['prefix' => 'store', 'as' => 'store.'], function () {
    Route::get('/', ['as' => 'index', 'uses' => 'StoreController@index']);
    Route::get('show/{id}', ['as' => 'show', 'uses' => 'StoreController@show']);
    Route::post('{listing_id}/add-to-cart', ['as' => 'add-to-cart', 'uses' => 'StoreCartController@addToCart']);
    Route::get('checkout', ['as' => 'checkout', 'uses' => 'StoreCheckoutController@getCheckout', 'middleware' => 'auth']);
    Route::post('checkout', ['as' => 'checkout', 'uses' => 'StoreCheckoutController@postCheckout', 'middleware' => 'auth']);
    Route::get('remove-from-cart/{productListingId}/{qty}/{model}', ['as' => 'cart.remove', 'uses' => 'StoreCartController@removeFromCart']);
    Route::get('update-cart-qty/{productListingId}/{qty}', ['as' => 'cart.update', 'uses' => 'StoreCartController@updateCart']);
    Route::get('clear-cart', ['as' => 'cart.clear', 'uses' => 'StoreCartController@clearCart']);
    Route::get('cart', ['as' => 'cart', 'uses' => 'StoreCartController@cart']);
    Route::any('search/{category?}', ['as' => 'search', 'uses' => 'StoreSearchController@search']);
});