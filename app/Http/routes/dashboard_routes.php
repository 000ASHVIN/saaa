<?php

/***********************
 * User Account Routes
 ***********************/
Route::group(['prefix' => 'account', 'as' => 'account.', 'middleware' => 'auth'], function() {
    Route::get('billing/return', ['as' => 'billing.return', 'uses' => 'Account\BillingController@return']);
    Route::post('billing/card', ['as' => 'billing.card', 'uses' => 'Account\BillingController@addCard']);
    Route::post('billing/primary', ['as' => 'billing.primary', 'uses' => 'Account\BillingController@primary']);
    Route::post('billing/remove', ['as' => 'billing.remove', 'uses' => 'Account\BillingController@remove']);
    Route::post('billing', ['as' => 'billing.store', 'uses' => 'Account\BillingController@store']);
});

Route::post('donation/billing/store', ['as' => 'billing.fetchstore', 'uses' => 'Account\BillingController@billingstore']);

Route::get('dashboard/edit/cell_verification', ['as' => 'dashboard.edit.cell_verification', 'uses' => 'Dashboard\DashboardController@getOtpView']);

/***********************
 * Invitation Route
 ***********************/
Route::get('accept/invite/{company}/{token}', ['as' => 'accept_company_invite', 'uses' => 'Dashboard\CompanyController@accept_invite']);

/***********************
 * User Dashboard Routes
 ***********************/
Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'Dashboard\DashboardController@show']);
Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {

    Route::post('contact_email', ['as' => 'contact_email', 'uses' => 'Dashboard\DashboardController@contact_email']);
    Route::get('general', ['as' => 'general.index', 'uses' => 'Dashboard\DashboardController@general']);

    // Webinars on demand
    Route::get('webinars_on_demand/{category?}', ['as' => 'webinars_on_demand.index', 'uses' => 'Dashboard\DashboardController@webinars_on_demand']);
    Route::get('webinars_on_demand/{video}/links-and-resources', ['as' => 'video.links-and-resources', 'uses' => 'Dashboard\DashboardController@getVideoLinksAndResources']);
    Route::get('webinars_on_demand/{video}/cpd/claim', ['as' => 'video.cpd.claim', 'uses' => 'Dashboard\DashboardController@claimVideoCPD']);

    Route::get('billing', ['as' => 'billing.index', 'uses' => 'Account\BillingController@index']);
    Route::get('billing/paymentMethod', ['as' => 'billing.paymentMethod', 'uses' => 'UpdateUserPaymentMethodController@store']);

    // Webinars
    Route::group(['prefix' => 'webinars', 'as' => 'webinars.'], function (){
        Route::get('watch/{slug}', ['as' => 'watch', 'uses' => 'LiveWebinarController@show']);
    });

    // User Invoices
    Route::get('invoices', ['as' => 'invoices', 'uses' => 'Dashboard\DashboardController@getInvoices']);
    Route::get('invoices/overdue', ['as' => 'invoices.overdue', 'uses' => 'Dashboard\DashboardController@getOverdueInvoices']);
    Route::get('statement', ['as' => 'statement', 'uses' => 'Dashboard\DashboardController@getAccountStatement']);
    Route::get('statement/send', ['as' => 'send_statement', 'uses' => 'Dashboard\DashboardController@send_statement']);

    // Invoice orders.
    // User Orders
    Route::get('invoice_orders', ['as' => 'invoice_orders', 'uses' => 'Dashboard\DashboardController@getInvoiceOrders']);

    Route::group(['middleware' => ['contact-details', 'overdue-subscription']], function () {
        //User Quiz
        Route::get('quiz', ['as' => 'quiz', 'uses' => 'Dashboard\DashboardController@getQuiz']);

        // User Events
        Route::get('events', ['as' => 'events', 'uses' => 'Dashboard\DashboardController@getEvents']);
        Route::get('articles', ['as' => 'articles', 'uses' => 'Dashboard\DashboardController@getarticles']);
        Route::get('tickets/{ticketId}/links-and-resources', ['as' => 'tickets.links-and-resources', 'uses' => 'Dashboard\DashboardController@getTicketLinksAndResources']);
        Route::get('tickets/{ticketId}/cpd/claim', ['as' => 'tickets.cpd.claim', 'uses' => 'Dashboard\DashboardController@claimCPD']);

        // User Products
        Route::get('products', ['as' => 'products', 'uses' => 'Dashboard\DashboardController@getProducts']);

        // User CPDs
        Route::get('cpd', ['as' => 'cpd', 'uses' => 'Dashboard\CpdsController@index']);
        Route::post('update/{cpdId}', ['as' => 'cpd.update', 'uses' => 'Dashboard\CpdsController@update']);
        Route::post('remove/{cpdId}', ['as' => 'cpd.destroy', 'uses' => 'Dashboard\CpdsController@destroy']);
        Route::get('cpd/{cpdId}/certificate', ['as' => 'cpd.certificate', 'uses' => 'Dashboard\CertificatesController@show']);
        Route::get('cpd/{cpdId}/certificate/download', ['as' => 'cpd.certificate.download', 'uses' => 'Dashboard\CertificatesController@download']);

        // User Profile Edit
        Route::get('edit', ['as' => 'edit', 'uses' => 'Dashboard\DashboardController@getEdit']);
        Route::post('edit', ['as' => 'edit', 'uses' => 'Dashboard\DashboardController@postEdit']);

        // User Support Tickets
        Route::get('support_tickets', ['as' => 'support_tickets', 'uses' => 'Dashboard\DashboardController@getTickets']);

        // Update User ID Number & Email Address
        Route::post('update/{user}/id_number', ['as' => 'id_number_update', 'uses' => 'Dashboard\DashboardController@update_id_number_request']);
        Route::post('update/{user}/email_address', ['as' => 'email_address_update', 'uses' => 'Dashboard\DashboardController@update_email_address_request']);

        //Assessments
        Route::get('assessments/{id}', ['as' => 'assessments.show', 'uses' => 'Dashboard\AssessmentsController@show']);
        Route::post('assessments/{id}/mark', ['as' => 'assessments.mark', 'uses' => 'Dashboard\AssessmentsController@mark']);
        Route::get('assessments/lastresult/{id}', ['as' => 'assessments.lastresult', 'uses' => 'Dashboard\AssessmentsController@lastResult']);
        Route::get('tickets/{ticketId}/assessments/{id}/lastresult', ['as' => 'assessments.lastresult', 'uses' => 'Dashboard\AssessmentsController@lastResult']);

        // Download Webinar
        Route::get('downloaded_webinars', ['as' => 'downloaded_webinars', 'uses' => 'Dashboard\DashboardController@downlaoded_webinars']);
        Route::post('download/webinar/{webinar}', ['as' => 'download_webinar', 'uses' => 'Dashboard\DashboardController@download_webinar']);
    });

    Route::post('update-npo-registration-number', ['as' => 'update-npo-registration-number', 'uses' => 'Dashboard\DashboardController@postUpdateNPORegistrationNumber']);
    Route::post('cipc-update-free-event', ['as' => 'cipc-update-free-event', 'uses' => 'Dashboard\DashboardController@postUpdateCipcUpdateFreeEvent']);
    Route::post('update-contact-details', ['as' => 'update-contact-details', 'uses' => 'Dashboard\DashboardController@postUpdateContactDetails']);
    Route::post('pmc-discount-attorneys', ['as' => 'pmc-discount-attorneys', 'uses' => 'Dashboard\DashboardController@postUpdatePMCDiscount']);
    Route::post('pmc-discount-accountants', ['as' => 'pmc-discount-accountants', 'uses' => 'Dashboard\DashboardController@postUpdatePMCAccountantsDiscount']);

    // User Rewards
    Route::get('rewards', ['as' => 'rewards', 'uses' => 'Dashboard\DashboardController@rewards']);

    Route::group(['prefix' => 'edit', 'as' => 'edit.'], function () {

        // User Subscriptions
        Route::get('subscription', ['as' => 'subscription', 'uses' => 'Dashboard\DashboardController@getSubscription']);
        Route::post('subscription/cancel', ['as' => 'subscription.cancel', 'uses' => 'Dashboard\DashboardController@cancelSub']);

        // User Billing Information
        Route::get('billing_information', ['as' => 'billing_information', 'uses' => 'Dashboard\DashboardController@billing_information']);
        Route::post('billing_information/update', ['as' => 'billing_information.update', 'uses' => 'Dashboard\DashboardController@update_billing_information']);

        // User Privacy
        Route::get('privacy', ['as' => 'privacy', 'uses' => 'Dashboard\DashboardController@getPrivacy']);

        // User Password Change
        Route::get('password', ['as' => 'password', 'uses' => 'Dashboard\DashboardController@getPassword']);
        Route::post('password', ['as' => 'password', 'uses' => 'Dashboard\DashboardController@postPassword']);

        //OTP Changes
        Route::get('cell', ['as' => 'cell', 'uses' => 'Dashboard\DashboardController@getCell']);
        Route::post('cell_verification', ['as' => 'post_cell_verification', 'uses' => 'Dashboard\DashboardController@postOtpVerification']);

        // User Avatar Update
        Route::get('avatar', ['as' => 'avatar', 'uses' => 'Dashboard\DashboardController@getAvatar']);
        Route::post('avatar', ['as' => 'avatar', 'uses' => 'Dashboard\DashboardController@StoreAvatar']);

        // User Addresses
        Route::get('addresses', ['as' => 'addresses', 'uses' => 'Dashboard\AddressesController@getAddresses']);
        Route::post('addresses', ['as' => 'addresses.create', 'uses' => 'Dashboard\AddressesController@store']);
        Route::delete('addresses/{id}', ['as' => 'addresses.delete', 'uses' => 'Dashboard\AddressesController@destroy']);
        Route::post('addresses/{id}/primary', ['as' => 'addresses.set_primary', 'uses' => 'Dashboard\AddressesController@setPrimary']);
    });

    // Debt Process
    Route::get('settle_account/{secret}', ['as' => 'settle_account', 'uses' => 'Dashboard\DebtController@store']);
    Route::get('verify_account/{secret}', ['as' => 'security_question', 'uses' => 'Dashboard\DebtController@security_question']);
    Route::post('verify_account/{secret}', ['as' => 'security_question', 'uses' => 'Dashboard\DebtController@post_security_question']);
    Route::post('store_debit_order/{user}/debt_arrangement', ['as' => 'store_debit_order', 'uses' => 'Dashboard\DebtController@store_debit_order']);

    // EFT Process
    Route::get('settle_account/{user}/eft_payment_option', ['as' => 'settle_using_eft_payment_option', 'uses' => 'Dashboard\DebtController@eft_payment']);
    Route::post('settle_account/{user}/eft_payment_option', ['as' => 'settle_using_eft_payment_option', 'uses' => 'Dashboard\DebtController@store_eft_payment']);

    Route::group(['prefix' => 'wallet', 'as' => 'wallet.'], function (){
        Route::get('/', ['as' => 'index', 'uses' => 'Dashboard\WalletController@index']);
        Route::post('store/{user}', ['as' => 'store', 'uses' => 'Dashboard\WalletController@store']);
        Route::get('pay/{userId}/{InvoiceId}', ['as' => 'pay', 'uses' => 'Dashboard\WalletController@payInvoice']);
        Route::get('export/{userId}', ['as' => 'export', 'uses' => 'Dashboard\WalletController@exportTransactions']);
        Route::post('topup/{userId}/{card}', ['as' => 'topup', 'uses' => 'Dashboard\WalletController@topup']);
        Route::post('withdrawal/{userId}', ['as' => 'withdrawal', 'uses' => 'Dashboard\WalletController@withdrawal']);
    });

    Route::group(['prefix' => 'company', 'as' => 'company.'], function (){
        Route::get('/', ['as' => 'index', 'uses' => 'Dashboard\CompanyController@index']);
        Route::get('setup', ['as' => 'create', 'uses' => 'Dashboard\CompanyController@create']);
        Route::post('setup', ['as' => 'store', 'uses' => 'Dashboard\CompanyController@store']);
        Route::get('invite', ['as' => 'invite', 'uses' => 'Dashboard\CompanyController@invite']);
        Route::get('pending', ['as' => 'pending', 'uses' => 'Dashboard\CompanyController@pending']);
        Route::post('saveCompany', ['as' => 'saveCompany', 'uses' => 'Dashboard\CompanyController@saveCompany']);
        Route::post('invite', ['as' => 'process_invite', 'uses' => 'Dashboard\CompanyController@process_invite']);

        Route::post('store_invite', ['as' => 'store_invite', 'uses' => 'Dashboard\CompanyController@store_invite']);
        Route::post('store_invite_company', ['as' => 'store_invite_company', 'uses' => 'Dashboard\CompanyController@store_invite_company']);
        Route::post('bulkinvite', ['as' => 'process_bulk_invite', 'uses' => 'Dashboard\CompanyController@bulk_invite']);
        Route::get('resend/{token}', ['as' => 'resend_invite', 'uses' => 'Dashboard\CompanyController@resend_invite']);
        Route::get('cancel/{token}', ['as' => 'cancel_invite', 'uses' => 'Dashboard\CompanyController@cancel_invite']);
        Route::get('cancel/membership/{company}/{user}', ['as' => 'cancel_membership', 'uses' => 'Dashboard\CompanyController@cancel_membership']);
 
        Route::get('allocate/membership/{company}/{user}', ['as' => 'allocate_membership', 'uses' => 'Dashboard\CompanyController@allocate_membership']);

       
        Route::post('addusers', ['as' => 'add_users', 'uses' => 'Dashboard\CompanyController@add_users']);
        Route::get('{userId}/staff/certificates/download', ['as' => 'staff.certificates.download', 'uses' => 'Dashboard\CompanyController@downloadAllCertificates']);
    });

    Route::group(['prefix' => 'cycle', 'as' => 'cycle.'], function (){
        Route::post('store', ['as' => 'create', 'uses' => 'Dashboard\CpdCycleController@store']);
        Route::post('update/{CycleId}', ['as' => 'update', 'uses' => 'Dashboard\CpdCycleController@update']);
    });

    Route::group(['prefix' => 'courses', 'as' => 'courses.'], function (){
        Route::get('/', ['as' => 'index', 'uses' => 'Dashboard\CoursesController@index']);
        Route::get('show/{courseId}', ['as' => 'show', 'uses' => 'Dashboard\CoursesController@show']);
    });

    Route::group(['prefix' => 'chat', 'as' => 'chat.'], function () {
        Route::get('/messages', ['as' => 'messages' , 'uses' => 'Dashboard\ChatController@listMessages']);
        Route::post('/message', ['as' => 'message' , 'uses' => 'Dashboard\ChatController@saveMessage']);
        Route::get('/room/create', ['as' => 'room.create' , 'uses' => 'Dashboard\ChatController@createChatRoom']);
        Route::post('/room/end/{roomId?}', ['as' => 'room.end' , 'uses' => 'Dashboard\ChatController@endChatRoom']);
    });
    
    Route::get('event/{event_id}/{user_id}', ['as' => 'event.check', 'uses' => 'Dashboard\DashboardController@check_event']);
});