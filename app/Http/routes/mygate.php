<?php

/***********************
 * Payment Webhook
 ***********************/
Route::post('payment/afterCheckThreeDs', 'Payments\PaymentsController@afterCheckThreeDs');
Route::post('payment/checkThreeDs', 'Payments\PaymentsController@checkThreeDs');
// Route::post('payment/process', 'Payments\PaymentsController@paymentWebHook');