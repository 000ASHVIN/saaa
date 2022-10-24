<?php

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/event-name/{name}', ['as' => 'UpdateNewEvent', 'uses' => 'AdminEventSyncController@UpdateNewEvent']); 
});

/***********************
 * Administrative Routes
 ***********************/
Route::group(['middleware' => 'permission:access-admin-section'], function () {
    Route::get('admin', ['as' => 'admin.dashboard', 'uses' => 'Admin\DashboardController@showDashboard']);

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {

        // Pricing Group
        Route::group(['prefix' => 'pricing_group', 'as' => 'admin.pricing_group.'], function (){
            Route::get('/', ['as' => 'index', 'uses' => 'AdminPricingGroupController@index']);
            Route::get('create', ['as' => 'create', 'uses' => 'AdminPricingGroupController@create']);
            Route::post('store', ['as' => 'store', 'uses' => 'AdminPricingGroupController@store']);
            Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'AdminPricingGroupController@edit']);
            Route::post('update/{id}', ['as' => 'update', 'uses' => 'AdminPricingGroupController@update']);
            Route::get('destroy/{id}', ['as' => 'destroy', 'uses' => 'AdminPricingGroupController@destroy']);
        });


            // Get all event list
            Route::get('/event-list', ['as' => 'geteventlist', 'uses' => 'AdminEventSyncController@getEventList']); 
            Route::post('/check-event', ['as' => 'checkevent', 'uses' => 'AdminEventSyncController@checkevent']); 
            Route::post('/event-list-sync', ['as' => 'geteventlistsync', 'uses' => 'AdminEventSyncController@getEventListSync']); 

        Route::group(['prefix' => 'course', 'as' => 'admin.course.'], function (){
            // Get all course list
            Route::get('/list', ['as' => 'sync.list', 'uses' => 'Course\AdminCourseSyncController@index']);
            Route::post('/sync', ['as' => 'sync', 'uses' => 'Course\AdminCourseSyncController@syncCourse']);
            Route::get('/synced_list', ['as' => 'synced_list', 'uses' => 'Course\AdminCourseSyncController@getSyncedCoursesList']);
        });
        Route::get('members/{member}/add_moodle', ['as' => 'member.add_moodle', 'uses' => 'MembersController@create_moodle_user']);

        Route::resource('seo_data', 'SeoDataController');

         // SEO Changes
         Route::get('/seo/{name?}', ['as' => 'seo', 'uses' => 'SEOController@index']); 
         Route::get('/seo/edit/{id}/{name}', ['as' => 'seoEdit', 'uses' => 'SEOController@edit']); 
         Route::post('/seo/update/{id}/{name}', ['as' => 'seoUpdate', 'uses' => 'SEOController@update']); 
         Route::post('/seo/search/', ['as' => 'seoSearch', 'uses' => 'SEOController@search']);
         Route::post('/seo/search_new/', ['as' => 'seoSearchNew', 'uses' => 'SEOController@search_new']);
        Route::resource('redirect', 'RedirectController');
        Route::get('redirect/destroy/{id}', ['as' => 'redirect.remove', 'uses' => 'RedirectController@destroy']);
        Route::resource('professional_bodies', 'ProfessionalBodyController');
        Route::resource('professional_bodies/designation', 'ProfessionalBodyDesignationController');

        Route::post('my_sales/event_registrations', ['as' => 'my_sales_export_event_registrations', 'uses' => 'MySaleController@get_event_registrations']);
        Route::post('my_sales/cpd_subscription_registrations', ['as' => 'my_sales_export_cpd_subscription_registrations', 'uses' => 'MySaleController@get_cpd_subscription_registrations']);
        Route::resource('my_sales', 'MySaleController');

        // Webinars
        Route::group(['prefix' => 'live_webinars', 'as' => 'admin.live_webinars.'], function (){
            Route::get('index', ['as' => 'index', 'uses' => 'LiveWebinarController@index']);
            Route::get('create', ['as' => 'create', 'uses' => 'LiveWebinarController@create']);
            Route::post('store', ['as' => 'store', 'uses' => 'LiveWebinarController@store']);
            Route::get('edit/{slug}', ['as' => 'edit', 'uses' => 'LiveWebinarController@edit']);
            Route::post('update/{slug}', ['as' => 'update', 'uses' => 'LiveWebinarController@update']);
            Route::get('destroy/{slug}', ['as' => 'destroy', 'uses' => 'LiveWebinarController@destroy']);
        });

        // Professions
        Route::group(['prefix' => 'professions', 'as' => 'admin.professions.'], function (){
            Route::get('index', ['as' => 'index', 'uses' => 'ProfessionController@index']);
            Route::get('create', ['as' => 'create', 'uses' => 'ProfessionController@create']);
            Route::post('store', ['as' => 'store', 'uses' => 'ProfessionController@store']);
            Route::get('edit/{slug}', ['as' => 'edit', 'uses' => 'ProfessionController@edit']);
            Route::post('update/{slug}', ['as' => 'update', 'uses' => 'ProfessionController@update']);
            Route::get('destroy/{slug}', ['as' => 'destroy', 'uses' => 'ProfessionController@destroy']);
        });


        Route::get('custom_payments_selected', ['as' => 'admin.custom_payments_selected', 'uses' => 'DashboardController@custom_payments_selected']);
        Route::post('custom_payments_selected', ['as' => 'admin.custom_payments_selected', 'uses' => 'DashboardController@export_custom_payments_selected']);

        Route::get('crype_email', ['as' => 'admin.crypt.index', 'uses' => 'CryptEmailAddressController@index']);
        Route::post('crype_email', ['as' => 'admin.crypt.store', 'uses' => 'CryptEmailAddressController@store']);

        Route::get('tax_update_subscribers', ['as' => 'admin.tax_update_subscribers', 'uses' => 'getSubscribersForExport@getTaxUpdateSubscribers']);
        Route::get('settings', ['as' => 'admin.settings', 'uses' => 'AdminSettingsController@index']);
        Route::post('settings/save', ['as' => 'admin.settings.store', 'uses' => 'AdminSettingsController@store']);

        /*
         * New membership plans
         */
        Route::group(['prefix' => 'plans', 'as' => 'admin.plans.', 'middleware' => 'permission:access-admin-plans'], function (){
            Route::get('/', ['as' => 'index', 'uses' => 'MembershipPlanController@index']);
            Route::get('create', ['as' => 'create', 'uses' => 'MembershipPlanController@create']);
            Route::post('create', ['as' => 'store', 'uses' => 'MembershipPlanController@store']);
            Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'MembershipPlanController@edit']);
            Route::post('edit/{id}', ['as' => 'update', 'uses' => 'MembershipPlanController@update']);
            Route::post('destroy/{id}', ['as' => 'destroy', 'uses' => 'MembershipPlanController@destroy']);
            Route::post('export/plan//invoices{id}', ['as' => 'export_invoices', 'uses' => 'MembershipPlanController@export_invoices']);

            /*
             * New features for membership plans
             */
            Route::group(['prefix' => 'features', 'as' => 'features.', 'middleware' => 'permission:access-admin-plan-features'], function (){
                Route::get('byo_default', ['as' => 'byo_default.index', 'uses' => 'MembershipPlanFeatureController@default_byo_features']);
                Route::post('byo_default', ['as' => 'byo_default.update', 'uses' => 'MembershipPlanFeatureController@default_byo_features_update']);

                Route::get('/', ['as' => 'index', 'uses' => 'MembershipPlanFeatureController@index']);
                Route::get('create', ['as' => 'create', 'uses' => 'MembershipPlanFeatureController@create']);
                Route::post('create', ['as' => 'store', 'uses' => 'MembershipPlanFeatureController@store']);
                Route::get('edit/{feature}', ['as' => 'edit', 'uses' => 'MembershipPlanFeatureController@edit']);
                Route::post('edit/{feature}', ['as' => 'update', 'uses' => 'MembershipPlanFeatureController@update']);
                Route::get('destroy/{feature}', ['as' => 'destroy', 'uses' => 'MembershipPlanFeatureController@destroy']);
            });

            /*
             * Practice plan tabs
             */
            Route::group(['prefix' => 'practice_plan', 'as' => 'practice_plan.', 'middleware' => 'permission:access-admin-plan-features'], function (){
                Route::get('create', ['as' => 'create', 'uses' => 'PracticePlanTabsController@create']);
                Route::post('create', ['as' => 'store', 'uses' => 'PracticePlanTabsController@store']);
                Route::get('edit/{practice_plan_tab}', ['as' => 'edit', 'uses' => 'PracticePlanTabsController@edit']);
                Route::post('edit/{practice_plan_tab}', ['as' => 'update', 'uses' => 'PracticePlanTabsController@update']);
                Route::get('destroy/{practice_plan_tab}', ['as' => 'destroy', 'uses' => 'PracticePlanTabsController@destroy']);
            });

        });

        Route::get('debit_orders', ['as' => 'admin.debit_orders.index', 'uses' => 'DebitOrderController@index']);
        Route::post('debit_orders/search', ['as' => 'admin.debit_orders.search', 'uses' => 'DebitOrderController@search']);
        Route::post('debit_orders/export_search', ['as' => 'admin.debit_orders.export_search', 'uses' => 'DebitOrderController@export_search']);

        Route::post('debit_orders/export/{confirmed?}', ['as' => 'admin.debit_orders.export', 'uses' => 'DebitOrderController@export']);
        Route::post('debit_orders/update/{id}', ['as' => 'admin.debit_orders.update', 'uses' => 'DebitOrderController@update']);
        Route::get('debit_orders/remove/{id}', ['as' => 'admin.debit_orders.destroy', 'uses' => 'DebitOrderController@destroy']);

        Route::group(['prefix' => 'cpd_report'], function (){
            Route::get('pre_registration', ['as' => 'admin.cpd_report.pre_registration', 'uses' => 'CpdReportController@pre_registration']);
            Route::get('api_pre_registrations', ['as' => 'admin.cpd_report.api_pre_registrations', 'uses' => 'CpdReportController@api_pre_registrations']);
            Route::get('cpd_renewals', ['as' => 'admin.cpd_report.cpd_renewals', 'uses' => 'CpdReportController@cpd_renewals']);
            Route::get('api_cpd_renewals', ['as' => 'admin.cpd_report.api_cpd_renewals', 'uses' => 'CpdReportController@api_cpd_renewals']);
        });

        Route::group(['middleware' => 'permission:can-view-stats'], function (){
            Route::get('stats/members/{plan?}', ['as' => 'admin.stats.members', 'uses' => 'MembershipStatsController@show']);
            Route::post('stats/members/{plan?}/export', ['as' => 'admin.stats.members.export', 'uses' => 'MembershipStatsController@export']);

            Route::get('stats/installments', ['as' => 'admin.stats.installments', 'uses' => 'StatsController@getInstallments']);
            Route::get('stats/orders', ['as' => 'admin.stats.orders', 'uses' => 'StatsController@getOrders']);
            Route::get('stats/registrations', ['as' => 'admin.stats.registrations', 'uses' => 'StatsController@getRegistrations']);
            Route::get('stats/mailers', ['as' => 'admin.stats.mailers', 'uses' => 'StatsController@getMailers']);

            Route::get('stats/payment_methods', ['as' => 'admin.stats.payment_methods', 'uses' => 'PaymentMethodController@index']);
            Route::post('stats/payment_methods/export/plan', ['as' => 'admin.stats.payment_methods.plan.export', 'uses' => 'PaymentMethodController@exportPlan']);
            Route::get('stats/payment_methods/show/{payment}', ['as' => 'admin.stats.payment_methods.show', 'uses' => 'PaymentMethodController@show']);
            Route::post('stats/payment_methods/export/{payment}', ['as' => 'admin.stats.payment_methods.export', 'uses' => 'PaymentMethodController@export']);

            Route::get('stats/cpd_courses_dashboard', ['as' => 'admin.stats.cpd_courses_dashboard', 'uses' => 'StatsController@getCpdCoursesDashboard']);
            Route::post('stats/cpd_courses_dashboard', ['as' => 'admin.stats.cpd_courses_dashboard', 'uses' => 'StatsController@getCpdCoursesDashboard']);

        });

        Route::get('event_reminders', ['as' => 'event_reminders', 'uses' => 'UpcomingEventReminderController@index']);
        Route::post('event_reminders', ['as' => 'event_reminders', 'uses' => 'UpcomingEventReminderController@runCommand']);
        Route::post('event_reminders/{key}/clear', ['as' => 'clear_reminders', 'uses' => 'UpcomingEventReminderController@clear']);

        Route::resource('members', 'MembersController');
        Route::get('member/find-user', ['as' => 'member.find_user', 'uses' => 'MembersController@find_user']);
        Route::get('member/{member}/details', ['as' => 'member.details', 'uses' => 'MembersController@member_details']);
        Route::get('{member}/activity_log', ['as' => 'member.activity_log', 'uses' => 'MembersController@user_activity_log']);
        Route::get('{member}/activity_log/export', ['as' => 'member.activity_log.export', 'uses' => 'MembersController@export_activity_log']);
        Route::get('{member}/account_notes', ['as' => 'member.account_notes', 'uses' => 'MembersController@account_notes']);
        Route::get('{member}/addresses', ['as' => 'member.addresses', 'uses' => 'MembersController@addresses']);
        Route::get('{member}/addresses/create', ['as' => 'member.addresses.create', 'uses' => 'MembersController@createAddress']);
        Route::get('{member}/{id}/addresses/edit', ['as' => 'member.addresses.edit', 'uses' => 'MembersController@editAddress']);
        Route::post('{member}/addresses/store', ['as' => 'member.addresses.store', 'uses' => 'MembersController@storeAddress']);
        Route::post('{member}/addresses/update', ['as' => 'member.addresses.update', 'uses' => 'MembersController@updateAddress']);
        Route::get('{member}/payment_method', ['as' => 'member.payment_method', 'uses' => 'MembersController@payment_method']);
        Route::get('{member}/edit', ['as' => 'member.edit', 'uses' => 'MembersController@edit']);
        Route::get('{member}/u-wallet', ['as' => 'member.wallet', 'uses' => 'MembersController@wallet']);
        Route::get('{member}/invoices', ['as' => 'member.invoices', 'uses' => 'MembersController@invoices']);
        Route::get('{member}/orders', ['as' => 'member.orders', 'uses' => 'MembersController@orders']);
        Route::get('orders/{id}/resend', ['as' => 'member.orders.resend', 'uses' => 'InvoiceOrderController@resend_invoice_order']);
        Route::get('{member}/generate_invoices', ['as' => 'member.generate_invoices', 'uses' => 'MembersController@generate_invoices']);
        Route::get('{member}/generate_order', ['as' => 'member.generate_order', 'uses' => 'MembersController@generate_order']);

        Route::get('{member}/generate_webinars_order', ['as' => 'member.generate_webinars_order', 'uses' => 'MembersController@generate_webinars_order']);

        Route::get('{member}/generate_practice_plan', ['as' => 'member.generate_practice_plan', 'uses' => 'MembersController@generate_practice_plan']);      
        Route::post('member/{memberId}/practice/order', ['as' => 'order_practice_plan_for_user', 'uses' => 'MembersController@generatePracticePlanOrder']);
        
        Route::get('{member}/renew_company_subscription', ['as' => 'member.renew_company_subscription', 'uses' => 'MembersController@renew_company_subscription']);
        Route::post('member/{memberId}/company_subscription/renew', ['as' => 'renew_company_subscription', 'uses' => 'MembersController@renewCompanySubscription']);

        Route::get('{member}/statement', ['as' => 'member.statement', 'uses' => 'MembersController@statement']);
        Route::get('{member}/generate_course_order', ['as' => 'member.generate_course_order', 'uses' => 'MembersController@generate_course_order']);        Route::get('{member}/statement', ['as' => 'member.statement', 'uses' => 'MembersController@statement']);
        Route::get('{member}/upgrade_subscription', ['as' => 'member.upgrade_subscription', 'uses' => 'MembersController@upgrade_subscription']);
        Route::get('{member}/{course}/change', ['as' => 'member.change_course', 'uses' => 'MembersController@change_course']);
        Route::get('{member}/{course}/cancel', ['as' => 'member.cancel_course', 'uses' => 'MembersController@cancel_course']);
        Route::get('{member}/courses', ['as' => 'member.courses', 'uses' => 'MembersController@courses']);
        Route::post('members/assign_course', ['as' => 'member.assign_course', 'uses' => 'MembersController@assign_course']);
        Route::post('members/update_subscriptions/{id}', ['as' => 'member.update_subscriptions', 'uses' => 'MembersController@update_subscriptions']);
        Route::post('members/event_code_apply/{id}', ['as' => 'member.event_code_apply', 'uses' => 'MembersController@event_code_apply']);
        Route::get('{member}/assessments', ['as' => 'member.assessments', 'uses' => 'MembersController@assessments']);
        Route::get('{member}/events', ['as' => 'member.events', 'uses' => 'MembersController@events']);
        Route::get('{member}/company', ['as' => 'member.company', 'uses' => 'MembersController@company']);

        Route::get('{member}/subscription/period/update', ['as' => 'member.subscription.period', 'uses' => 'MembersController@updateSubscriptionPeriod']);
        Route::get('{member}/subscription/allocate', ['as' => 'member.subscription.allocate', 'uses' => 'MembersController@allocateSubscription']);
        Route::get('{member}/subscription/remove', ['as' => 'member.subscription.remove', 'uses' => 'MembersController@removeSubscriptionAndMoveToFreePlan']);
        
        Route::get('{member}/sms', ['as' => 'member.sms', 'uses' => 'MembersController@sms']);
        Route::get('{member}/custom_topics', ['as' => 'member.custom_topics', 'uses' => 'MembersController@custom_topics']);
        Route::get('{member}/book_event', ['as' => 'member.book_event', 'uses' => 'MembersController@book_event']);
        Route::get('{member}/cpd_hours', ['as' => 'member.cpd_hours', 'uses' => 'MembersController@cpd_hours']);

        Route::get('{member}/find/{event}/pricings', ['as' => 'find_pricings', 'uses' => 'MembersController@find_pricings']);
        Route::get('{member}/find/{event}/venues', ['as' => 'find_pricings', 'uses' => 'MembersController@venues']);
        Route::get('{member}/find/{event}/dates', ['as' => 'find_pricings', 'uses' => 'MembersController@dates']);

        Route::get('members/forceSuspend/{user}', ['as' => 'member_force_suspend', 'uses' => 'MembersController@toggleForceSuspend']);
        Route::get('members/activeUserAndSubscription/{user}', ['as' => 'active_user_and_subscription', 'uses' => 'MembersController@activeUserAndSubscription']);
        Route::post('members/sendSMS/{user}', ['as' => 'member.sendSms', 'uses' => 'MembersController@sendSms']);
        Route::post('members/update/{id}', ['as' => 'members_update_profile', 'uses' => 'MembersController@update']);
        Route::get('subscribers', ['as' => 'subscribers', 'uses' => 'MembersController@getSubscribers']);
        Route::get('member/{memberId}/sign-in-as', ['as' => 'admin.members.sign-in-as', 'uses' => 'MembersController@signInAs']);
        Route::get('member/{memberId}/reset-password', ['as' => 'admin.members.reset-password', 'uses' => 'MembersController@resetPassword']);
        Route::post('member/{member}/cancel', ['as' => 'admin.members.cancel_profile', 'middleware' => 'permission:can-cancel-user-accounts', 'uses' => 'MembersController@cancelProfile']);
        Route::post('member/{member}/merge-profile', ['as' => 'admin.members.merge_profile', 'middleware' => 'permission:can-cancel-user-accounts', 'uses' => 'MembersController@mergeProfile']);
        Route::post('member/{member}/cancel/subscription', ['as' => 'admin.members.cancel_subscription', 'uses' => 'CancelMemberSubscriptionController@destroy']);
        Route::post('member/{member}/peach/otp', ['as' => 'admin.members.peach.otp', 'uses' => 'MembersController@otp']);

        Route::post('update_payment_method/{member}', ['as' => 'admin.update_payment_method', 'uses' => 'UpdateSubscriptionPaymentMethod@update']);

        Route::post('notes/store/{member}', ['as' => 'admin.notes.store', 'uses' => 'UserNotesController@store']);
        Route::post('notes/update/{member}', ['as' => 'admin.notes.update', 'uses' => 'UserNotesController@update']);
        Route::get('notes/complete/{note}/{user}', ['middleware' => 'permission:can-complete-ptp','as' => 'admin.notes.complete', 'uses' => 'UserNotesController@complete']);
        Route::get('notes/delete/{id}', ['middleware' => 'role:super', 'as' => 'admin.notes.destroy', 'uses' => 'UserNotesController@destroy']);

        Route::post('ptp/invoice/{id}', ['as' => 'admin.member.ptp_invoice_arrangment', 'uses' => 'MembersController@ptp_invoice_arrangment']);

        Route::post('member/{memberId}/events/register', ['as' => 'register_for_event', 'uses' => 'EventsController@register']);
        Route::post('member/{memberId}/events/register/order', ['as' => 'order_event_for_user', 'uses' => 'EventsController@generateOrder']);
        Route::post('member/{memberId}/course/order', ['as' => 'order_course_for_user', 'uses' => 'EventsController@generateCourseOrder']);

        Route::post('member/{memberId}/webinars_on_demand/order', ['as' => 'order_webinars_on_demand_for_user', 'uses' => 'MembersController@generateOrder']);

            Route::post('{member}/payment_method/change', ['as' => 'admin.member.change_payment_method', 'uses' => 'MembersController@changePaymentMethod']);

        Route::group(['middleware' => 'permission:can-edit-tickets'], function (){
            Route::post('tickets/{id}/cancel', ['as' => 'ticket_remove', 'uses' => 'TicketController@destroy']);
            Route::get('tickets/{id}/edit', ['as' => 'ticket_edit', 'uses' => 'TicketController@edit']);
            Route::post('tickets/{id}/update', ['as' => 'ticket_update', 'uses' => 'TicketController@update']);
            Route::post('tickets_bulk/destroy', ['as' => 'tickets.destroy', 'uses' => 'TicketController@bulkDestroy']);
        });

        Route::group(['middleware' => 'permission:can-access-roles-and-permissions'], function (){
            Route::get('roles', ['as' => 'admin.member_roles', 'uses' => 'RolesController@index']);
            Route::get('roles/create', ['as' => 'admin.member_roles.create', 'uses' => 'RolesController@create']);
            Route::post('roles/store', ['as' => 'admin.member_roles.store', 'uses' => 'RolesController@store']);
            Route::get('roles/assign_to_permissions', ['as' => 'admin.member_roles.assign_to_permissions', 'uses' => 'RolesController@permissions']);
            Route::post('roles/assign_to_permissions', ['as' => 'admin.member_roles.assign_to_permissions.store', 'uses' => 'RolesController@storePermissions']);
            Route::get('roles/edit/{id}', ['as' => 'admin.member_roles.assign_to_permissions.edit', 'uses' => 'RolesController@edit']);
            Route::post('roles/save/{id}', ['as' => 'admin.member_roles.assign_to_permissions.save', 'uses' => 'RolesController@update']);
            Route::get('remove/roles/from/{user}', ['as' => 'remove_roles_from_user', 'uses' => 'RolesController@removeFromUser']);
            Route::post('remove/role/from/{role}', ['as' => 'remove_role_from_user', 'uses' => 'RolesController@removeRoleFromUser']);
            Route::get('permissions', ['as' => 'admin.permissions', 'uses' => 'PermissionController@index']);
            Route::get('permissions/new', ['as' => 'admin.permissions_new', 'uses' => 'PermissionController@create']);
            Route::post('permissions/store', ['as' => 'admin.permissions_store', 'uses' => 'PermissionController@store']);
            Route::get('permissions/assign', ['as' => 'admin.permissions_assign', 'uses' => 'PermissionController@assign']);
            Route::post('permissions/assign', ['as' => 'admin.permissions_store_assigned', 'uses' => 'PermissionController@store_assigned']);
        });

        Route::group(['middleware' => 'permission:assign-events-to-plans'], function (){
            Route::get('events/assign-to-plans', ['as' => 'admin.events.assign-to-plans', 'uses' => 'EventsController@getAssignToPlans']);
            Route::post('events/assign-to-plans', ['as' => 'admin.events.assign-to-plans', 'uses' => 'EventsController@postAssignToPlans']);
        });

        Route::group(['middleware' => 'permission:can-assign-cpd-certificates'], function (){
            Route::get('event/{eventId}/venue/{venueId}/attendees', ['as' => 'admin.events.attendees', 'uses' => 'EventsController@getAttendees']);
            Route::post('event/{eventId}/venue/{venueId}/attendees/save', ['as' => 'admin.events.attendees.save', 'uses' => 'EventsController@saveAttendees']);
            Route::get('cpd/assign', ['as' => 'admin.cpd.assign', 'uses' => 'CPDController@getAssign']);


            Route::get('cpd/assign_webinars', ['as' => 'admin.cpd.assign_webinars', 'uses' => 'TicketController@MarkAsAttended']);
            Route::post('cpd/assign_webinars', ['as' => 'admin.cpd.post_assign_webinars', 'uses' => 'TicketController@PostMarkAsAttended']);
        });

        Route::group(['middleware' => 'permission:manage-event-venues'], function (){
            Route::get('events/venues', ['as' => 'show_event_venues', 'uses' => 'EventsController@venues']);
            Route::post('events/venues/{id}', ['as' => 'event_venues_close', 'uses' => 'EventsController@closevenues']);
        });

        // Event Creation
        Route::get('event/show/all', ['as' => 'admin.event.index', 'uses' => 'AdminEventsController@index']);
        Route::get('event/new/create', ['as' => 'admin.event.create', 'uses' => 'EventsController@create']);
        Route::post('event/new/store', ['as' => 'admin.event.store', 'uses' => 'AdminEventsController@store']);
        Route::post('event/search/', ['as' => 'admin.event.search', 'uses' => 'AdminEventsController@search']);

        Route::get('event/show/{slug}', ['as' => 'admin.event.show', 'uses' => 'AdminEventsController@show']);
        Route::post('event/show/{slug}', ['as' => 'admin.event.update', 'uses' => 'AdminEventsController@update']);

        // sync event 
        Route::get('event/sync/{slug}', ['as' => 'admin.event.sync', 'uses' => 'AdminEventsController@sync_event']);
        
        // Event Venues
        Route::post('event/{event}/venue/store/', ['as' => 'admin.event.venue.store', 'uses' => 'AdminEventsVenueController@store']);
        Route::post('event/{event}/{venue}/update', ['as' => 'admin.event.venue.update', 'uses' => 'AdminEventsVenueController@update']);
        Route::get('venue/{venue}/destroy/', ['as' => 'admin.event.venue.destroy','middleware' => 'permission:can-remove-venues', 'uses' => 'AdminEventsVenueController@destroy']);

        // Event Venue Dates
        Route::post('{venue}/dates/create', ['as' => 'admin.venues.dates.create', 'uses' => 'AdminVenuesDateController@store']);
        Route::post('event/{event}/{venue}/{date}/update/save', ['as' => 'admin.venues.dates.update', 'uses' => 'AdminVenuesDateController@update']);

        // Event Pricings
        Route::post('event/{event}/pricings/store', ['as' => 'admin.event.pricings.store', 'uses' => 'AdminEventPricingController@store']);
        Route::post('pricing/{id}/update', ['as' => 'admin.event.pricings.update', 'uses' => 'AdminEventPricingController@update']);
        Route::post('pricing/{id}/destroy', ['as' => 'admin.event.pricings.destroy', 'uses' => 'AdminEventPricingController@destroy']);

        // Event Features
        Route::post('{pricing}/features_synch/save', ['as' => 'admin.event.features.sync', 'uses' => 'AdminEventPricingFeatureController@update']);

        // Event Pricing Bodies
        Route::post('{pricing}/body_synch/save', ['as' => 'admin.event.pricing.bodies.sync', 'uses' => 'AdminEventPricingBodyFeatureController@update']);

        // Event Extra
        Route::post('extra/{event}_extra/store/', ['as' => 'admin.event.extra.store', 'uses' => 'EventExtraController@store']);
        Route::post('extra/{event}/{extra}/update/', ['as' => 'admin.event.extra.update', 'uses' => 'EventExtraController@update']);
        Route::get('extra/{event}/{extra}_extra/destroy/', ['as' => 'admin.event.extra.destroy', 'uses' => 'EventExtraController@destroy']);

        // Event Links
        Route::post('event/{event}/links/store', ['as' => 'admin.event.link.store', 'uses' => 'AdminEventLinksController@store']);
        Route::post('event/link_{link}/update', ['as' => 'admin.event.link.update', 'uses' => 'AdminEventLinksController@update']);

        // Event Discount Codes
        Route::post('{event}/discount_code/store', ['as' => 'admin.event.discount.store', 'uses' => 'AdminEventDiscountCodeController@store']);
        Route::post('discount_code/{code}/update', ['as' => 'admin.event.discount.update', 'uses' => 'AdminEventDiscountCodeController@update']);
        Route::get('discount_code/{code}/destroy', ['as' => 'admin.event.discount.destroy', 'uses' => 'AdminEventDiscountCodeController@destroy']);


        
        // course Discount Codes
        Route::post('{course}/discount_code/course/store', ['as' => 'admin.course.discount.store', 'uses' => 'AdminCoursesDiscountCodeController@store']);
        Route::post('discount_code/course/{code}/update', ['as' => 'admin.course.discount.update', 'uses' => 'AdminCoursesDiscountCodeController@update']);
        Route::get('discount_code/course/{code}/destroy', ['as' => 'admin.course.discount.destroy', 'uses' => 'AdminCoursesDiscountCodeController@destroy']);


        // Event Webinar Links
        Route::post('{event}/webinar/create', ['as' => 'admin.event.webinar.store', 'uses' => 'AdminEventWebinarController@store']);
        Route::post('webinar/{webinar}/update', ['as' => 'admin.event.webinar.update', 'uses' => 'AdminEventWebinarController@update']);
        Route::get('webinar/{webinar}/destroy', ['as' => 'admin.event.webinar.destroy', 'uses' => 'AdminEventWebinarController@destroy']);

        // Event Assessments
        Route::post('{event}/assessments/store', ['as' => 'admin.event.assessment.store', 'uses' => 'AdminAssessmentController@store']);

        // Export Event Stats to Excell File
        Route::get('event/export/{slug}', ['as' => 'admin.event.export.stats', 'uses' => 'AdminEventsController@exportStats']);

        // Upload to SendinBlue
        Route::get('event/upload/{slug}/sendinBlue', ['as' => 'admin.event.upload.stats', 'middleware' => 'role:super', 'uses' => 'AdminEventsController@uploadStats']);

        // Publish event to online store
        Route::get('event/publish/{slug}/store', ['as' => 'admin.event.upload.onlineStore', 'middleware' => 'role:super', 'uses' => 'AdminEventsController@onlineStore']);

        Route::get('event/notify/{id}/store', ['as' => 'admin.event.notify', 'middleware' => 'role:super', 'uses' => 'AdminEventsController@notify']);

        // Route::get('event/schedule-notifications/{id}', ['as' => 'admin.event.schedule-notifications', 'middleware' => 'role:super', 'uses' => 'AdminEventsController@scheduleNotifications']);

        // Event notifications
        Route::group(['as' => 'admin.event.notifications.', 'middleware' => 'role:super'], function(){

            Route::get('event/notifications', ['as' => 'index', 'uses' => 'AdminNewEventNotificationController@index']);
            Route::get('event/notifications/create', ['as' => 'create', 'uses' => 'AdminNewEventNotificationController@create']);
            Route::post('event/notifications/create', ['as' => 'store', 'uses' => 'AdminNewEventNotificationController@store']);
            Route::delete('event/notifications/{id}', ['as' => 'destroy', 'uses' => 'AdminNewEventNotificationController@destroy']);


        });

        // User Assessments Attempts
        Route::post('{attempt}/attempt/update', ['as' => 'admin.event.attempt.update', 'uses' => 'AdminAssessmentAttemptController@update']);
        Route::get('{attempt}/attempt/destroy', ['as' => 'admin.event.attempt.destroy', 'uses' => 'AdminAssessmentAttemptController@destroy']);

        Route::resource('events', 'EventsController');

            // synced events
            Route::resource('synced_events', 'SyncEventsController');
            Route::get('synced_event/show/{slug}', ['as' => 'admin.synced_event.show', 'uses' => 'SyncEventsController@show']);

        Route::get('download/promo_codes', ['as' => 'admin.event.promo_codes.download', 'uses' => 'AdminEventPromoCodeController@download']);
        Route::resource('promo_code', 'AdminEventPromoCodeController');

        // Presenters
        Route::resource('presenters', 'AdminPresenterController');
        Route::post('sort', ['as' => 'srotable', 'uses' => '\Rutorika\Sortable\SortableController@sort']);

        Route::resource('store_categories', 'CategoryController');
        Route::resource('products', 'ProductsController');
        Route::post('products/assign-listing/{productId}', ['as' => 'admin.products.assign-listing', 'uses' => 'ProductsController@assignListing']);
        Route::get('products/unassign-listing/{productId}/{listingId}', ['as' => 'admin.products.unassign-listing', 'uses' => 'ProductsController@unassignListing']);

        Route::post('product/{productId}/save/link', ['as' => 'admin.product.link.store', 'uses' => 'AdminStoreProductLinkController@store' ]);
        Route::post('product/{productId}/update/link', ['as' => 'admin.product.link.update', 'uses' => 'AdminStoreProductLinkController@update' ]);
        Route::get('product/{productId}/destroy/link', ['as' => 'admin.product.link.destroy', 'uses' => 'AdminStoreProductLinkController@destroy' ]);

        Route::post('product/{productId}/save/assessment', ['as' => 'admin.product.assessment.store', 'uses' => 'AdminStoreProductAssessmentController@store' ]);

        Route::resource('listings', 'ListingsController');
        Route::post('listings/assign-discount/{listingId}', ['as' => 'admin.listings.assign-discount', 'uses' => 'ListingsController@assignDiscount']);
        Route::get('listings/unassign-discount/{listingId}/{discountId}', ['as' => 'admin.listings.unassign-discount', 'uses' => 'ListingsController@unassignDiscount']);

        Route::resource('videos', 'VideosController');
        Route::get('video/search/', ['as' => 'admin.video.search', 'uses' => 'VideosController@search']);
        Route::get('video-providers/{videoProviderId}/videos', ['as' => 'admin.video-providers.videos', 'uses' => 'VideosController@getVideoProviderVideos']);

        // Video assessments
        Route::post('video/assessments/store/{video_id}', ['as' => 'admin.video.assessment.store', 'uses' => 'VideosController@syncAssessments']);
        Route::get('video/sync-resources/{video_id}', ['as' => 'admin.video.sync-resources', 'uses' => 'VideosController@syncResourcesFromEvent']);

        // Video Links
        Route::post('video/{video_id}/links/store', ['as' => 'admin.video.link.store', 'uses' => 'AdminVideoLinksController@store']);
        Route::post('video/link_{link}/update', ['as' => 'admin.video.link.update', 'uses' => 'AdminVideoLinksController@update']);
        Route::delete('video/{video}/link_{link}/delete', ['as' => 'admin.video.link.delete', 'uses' => 'AdminVideoLinksController@destroy']);

        // Webinar Series
        Route::resource('webinar_series', 'WebinarSeriesController');
        Route::post('webinar_series/assign_webinar/{series_id}', ['as' => 'admin.webinar_series.assign_webinar', 'uses' => 'WebinarSeriesController@assignWebinar']);
        Route::post('webinar_series/set_webinar_sequence/{series_id}', ['as' => 'admin.webinar_series.set_webinar_sequence', 'uses' => 'WebinarSeriesController@setWebinarSequence']);

        Route::post('recordings/create/{videoId}', ['as' => 'admin.recordings.create', 'uses' => 'RecordingsController@create']);
        Route::get('recordings/destroy/{recordingId}', ['as' => 'admin.recordings.destroy', 'uses' => 'RecordingsController@destroy']);

        Route::resource('orders', 'OrdersController');
        Route::post('shipping/{shippingInformationId}/update', ['as' => 'admin.shipping.update', 'uses' => 'OrdersController@updateShippingInformation']);

        Route::post('search', 'SearchController@search');
    
        Route::get('search-filter', ['as' => 'admin.searchData', 'uses' => 'SearchController@searchData']);
        Route::get('search', ['as' => 'admin.search', 'uses' => 'SearchController@index']);

        Route::group(['middleware' => 'role:super'], function(){
            Route::get('invoices/{id}/allocate-system', 'InvoicesController@allocateToSystem');
        });

        Route::group(['middleware' => 'role:super|accounts-administrator'], function(){
            Route::get('invoices/{id}/allocate', 'InvoicesController@getAllocate');
            Route::get('{member}/invoices/consolidate/all', ['as' => 'consolidate_invoices', 'uses' => 'InvoicesController@consolidate']);
            Route::post('invoices/{id}/cancel', 'InvoicesController@getCancel');
            Route::post('invoices/allocate', 'InvoicesController@postAllocate');
            Route::post('invoices/payments/{id}/delete', 'InvoicesController@getPaymentDelete');
            Route::post('members/invoices/create', 'InvoicesController@createSubscriptionInvoice');
            Route::post('members/courses/invoices/create', 'InvoicesController@createCourseInvoice');
            Route::post('members/invoices/CombinedInvoice', 'InvoicesController@createNewCombinedInvoice');
            Route::post('members/invoices/PrintedNotesInvoice', 'InvoicesController@createPrintedNotesInvoice');
            Route::post('members/invoice/{id}/credit', ['as' => 'credit_invoice', 'uses' => 'InvoicesController@applyCreditNote']);
        });

        Route::group(['middleware' => 'role:super|accounts-administrator'], function(){
            Route::get('invoice/order/{id}/allocate', ['as' => 'allocate_order_payment', 'uses' => 'InvoiceOrderController@getAllocate']);
            Route::post('invoice/order/{id}/discount', ['as' => 'post_discount_order', 'uses' => 'InvoiceOrderController@discount']);
            Route::post('invoice/order/{id}/cancel', ['as' => 'post_allocate_order_payment', 'uses' => 'InvoiceOrderController@postAllocate']);
            Route::post('invoice/order/{id}/allocate', ['as' => 'cancel_invoice_order', 'uses' => 'InvoiceOrderController@cancel']);
        });

        Route::get('members/invoice/{id}/resend', ['as' => 'resend_invoice', 'uses' => 'InvoicesController@resend_invoice']);
        Route::post('members/{member}/statement/send/', ['as' => 'send_statement', 'uses' => 'InvoicesController@send_statement']);

        // JobController Routes
        Route::get('jobs', ['as' => 'jobs.index', 'uses' => 'JobController@index']);
        Route::get('job/create', ['as' => 'jobs.create', 'uses' => 'JobController@create']);
        Route::get('job/applications', ['as' => 'applications', 'uses' => 'JobController@applications']);
        Route::post('job/create', ['as' => 'store', 'uses' => 'JobController@store']);
        Route::get('job/edit/{slug}', ['as' => 'jobs.edit', 'uses' => 'JobController@edit']);
        Route::post('job/edit/{slug}', ['as' => 'jobs.update', 'uses' => 'JobController@update']);

        //Department Routes
        Route::resource('departments', 'DepartmentController');

        //Assessments
        Route::get('assessments', ['as' => 'admin.assessments.index', 'uses' => 'AssessmentsController@index']);
        Route::get('assessments/create', ['as' => 'admin.assessments.create', 'uses' => 'AssessmentsController@create']);
        Route::get('assessments/{assessmentId}/edit', ['as' => 'admin.assessments.edit', 'uses' => 'AssessmentsController@edit']);
        Route::post('assessments/store', ['as' => 'admin.assessments.store', 'uses' => 'AssessmentsController@store']);

        Route::group(['middleware' => 'permission:can-import-and-export'], function (){
            //Imports

            Route::get('imports/claimed_invoices', ['as' => 'claimed_invoices_import', 'uses' => 'ClaimedInvoicesController@index']);
            Route::post('imports/claimed_invoices', ['as' => 'claimed_invoices_import', 'uses' => 'ClaimedInvoicesController@store']);

            Route::get('import-provider/{providerId}', ['as' => 'admin.import.provider', 'uses' => 'ImportsController@getProvider']);
            Route::post('import-provider/{providerId}', ['as' => 'admin.import.provider', 'uses' => 'ImportsController@postProvider']);
            Route::get('import-provider/{providerId}/imports', ['as' => 'admin.import.provider.imports', 'uses' => 'ImportsController@getImports']);
            Route::get('import-provider/{providerId}/import/{importId}', ['as' => 'admin.import.provider.import', 'uses' => 'ImportsController@getImport']);
            Route::get('import-provider/{providerId}/import/{importId}/{action}', ['as' => 'admin.import.provider.import.action', 'uses' => 'ImportsController@getPerformProviderAction']);
            Route::post('import-provider/{providerId}/import/{importId}/{action}', ['as' => 'admin.import.provider.import.action', 'uses' => 'ImportsController@postPerformProviderAction']);
        });

        Route::group(['middleware' => 'permission:can-export-event-registrations'], function (){
            Route::get('exports/event_registrations', ['as' => 'admin.exports.event_registrations', 'uses' => 'ExportsController@getEventRegistrations']);
            Route::post('exports/event_registrations', ['as' => 'admin.exports.event_registrations', 'uses' => 'ExportsController@postEventRegistrations']);
            Route::get('email_exports', ['as' => 'admin.exports.export_email_address', 'uses' => 'EmailListController@index']);
            Route::get('store_order_members', ['as' => 'admin.exports.all_store_order_members', 'uses' => 'EmailListController@store_order_members']);
            Route::get('all_webinar_tickets', ['as' => 'admin.exports.all_webinar_tickets', 'uses' => 'EmailListController@webinar_tickets']);
            Route::get('all_cpd_members_with_invoice', ['as' => 'admin.exports.all_cpd_members_with_invoice', 'uses' => 'EmailListController@cpd_members_with_invoice']);
        });

        Route::group(['middleware' => 'permission:view-reports'], function (){
            Route::group(['prefix' => 'reports', 'as' => 'admin.reports.'], function() {

                Route::get('upcoming_renewal/{type?}', ['as' => 'upcoming_renewal', 'uses' => 'ReportController@getUpcomingRenewal']);
                Route::get('upcoming_renewal_export/{type?}', ['as' => 'upcoming_renewal.export', 'uses' => 'ReportController@postUpcomingRenewal']);
                Route::get('datatable_upcoming_renewal/{type?}', ['as' => 'datatable_upcoming_renewal', 'uses' => 'ReportController@datatableUpcomingRenewal']);

                Route::get('reward_export', ['as' => 'reward_export', 'uses' => 'ReportController@reward_export']);
                Route::post('reward_export', ['as' => 'reward_export', 'uses' => 'ReportController@post_reward_export_export']);
                
                Route::get('courses/index', ['as' => 'get_courses_report', 'uses' => 'ReportController@course_reports']);
                Route::get('course/{course_id}/get_report', ['as' => 'extract_course_report', 'uses' => 'ReportController@extract_course_report']);

                Route::get('wod_export', ['as' => 'wod_export', 'uses' => 'ReportController@wod_export']);
                Route::post('wod_export', ['as' => 'post_wod_export', 'uses' => 'ReportController@post_wod_export']);
                
                Route::get('professional_body', ['as' => 'professional_body', 'uses' => 'ReportController@getProfessionalBody']);
                Route::post('professional_body', ['as' => 'professional_body', 'uses' => 'ReportController@postProfessionalBody']);

                
                Route::group(['prefix' => 'payments', 'as' => 'payments.'], function() {
                    Route::get('Subscription/all', ['as' => 'cpd_stats_all', 'uses' => 'ReportController@cpd_stats_all']);
                    Route::get('Subscription/allCourses', ['as' => 'course_stats_all', 'uses' => 'ReportController@course_stats_all']);
                    Route::get('Subscription/Monthly', ['as' => 'cpd_stats', 'uses' => 'ReportController@monthlyCPDReport']);
                    Route::get('income', ['as' => 'income', 'uses' => 'ReportController@getIncome']);
                    Route::post('income', ['as' => 'income.store', 'uses' => 'ReportController@postIncome']);
                    Route::post('export', ['as' => 'income.export', 'uses' => 'ReportController@postExport']);
                    Route::get('ledger', ['as' => 'ledger', 'uses' => 'ReportController@getLedger']);

                    Route::get('debtors', ['as' => 'debtors', 'uses' => 'ReportController@getDebtors']);
                    Route::post('debtors/date_range', ['as' => 'debtors.date_range_post', 'uses' => 'ReportController@postDebtors']);
                    Route::post('debtors/export', ['as' => 'debtors.date_range', 'uses' => 'ReportController@exportDebtors']);

                    Route::get('outstanding', ['as' => 'outstanding-invoices', 'uses' => 'ReportController@outstanding']);
                    Route::get('outstanding/export', ['as' => 'outstanding-invoices-export', 'uses' => 'ReportController@exportOustanding']);
                    Route::get('custom_transactions', ['as' => 'custom-transactions', 'uses' => 'ReportController@customDebtTransactions']);
                    Route::post('custom_transactions/export', ['as' => 'custom-transactions-export', 'uses' => 'ReportController@exportCustomDebtTransactionsExport']);
                    Route::get('credited_invoices', ['as' => 'credited_invoices', 'uses' => 'ReportController@credited_invoices']);
                    Route::post('credited_invoices', ['as' => 'credited_invoices', 'uses' => 'ReportController@post_credited_invoices_export']);

                    Route::get('claim_invoices', ['as' => 'claim_invoices', 'uses' => 'ReportController@claim_invoices']);
                    Route::post('claim_invoices', ['as' => 'claim_invoices_post', 'uses' => 'ReportController@claim_invoices_post']);

                    Route::get('aging_report', ['as' => 'aging_report', 'uses' => 'ReportController@download_againg_report']);

                    Route::get('extract_invoices', ['as' => 'extract_invoices', 'uses' => 'ReportController@extract_invoices']);
                    Route::post('post_extract_invoices', ['as' => 'post_extract_invoices', 'uses' => 'ReportController@post_extract_invoices']);

                    Route::get('download_course', ['as' => 'download_course', 'uses' => 'ReportController@download_course']); 
                    Route::post('post_download_course', ['as' => 'post_download_course', 'uses' => 'ReportController@post_download_course']);


                    Route::get('talk_to_human', ['as' => 'talk_to_human', 'uses' => 'ReportController@talk_to_human']); 
                    Route::post('post_talk_to_human', ['as' => 'post_talk_to_human', 'uses' => 'ReportController@post_talk_to_human']);

                    Route::get('extract_transactions', ['as' => 'extract_transactions', 'uses' => 'ReportController@extract_transactions']);
                    Route::post('extract_transactions', ['as' => 'post_extract_transactions', 'uses' => 'ReportController@post_extract_transactions']);

                    // Monthly Income Report
                    Route::get('income_report/monthly', ['as' => 'monthly_income_report', 'uses' => 'ReportController@get_monthly_income_report']);
                    Route::post('income_report/monthly', ['as' => 'post_monthly_income_report', 'uses' => 'ReportController@monthly_income_report']);

                    // Invoice Orders extract
                    Route::get('purchase_orders/extract', ['as' => 'purchase_orders.extract', 'uses' => 'InvoiceOrderReportController@get_extract']);
                    Route::post('purchase_orders/extract', ['as' => 'purchase_orders.post_extract', 'uses' => 'InvoiceOrderReportController@post_extract']);

                    Route::get('outstanding_orders_p_p', ['as' => 'outstanding_orders_p_p', 'uses' => 'InvoiceOrderReportController@getExtractFromDatePp']);
                    Route::post('results_orders_outstanding_p_p', ['as' => 'results_orders_outstanding_p_p', 'uses' => 'InvoiceOrderReportController@postExtractFromDatePp']);
                    Route::post('export_orders_results_outstanding_p_p/{from}/{to}', ['as' => 'export_orders_results_outstanding_p_p', 'uses' => 'InvoiceOrderReportController@export_results_outstanding_p_p']);

                    Route::get('outstanding_p_p', ['as' => 'outstanding_p_p', 'uses' => 'ExtractInvoiceController@getExtractFromDatePp']);
                    Route::post('results_outstanding_p_p', ['as' => 'results_outstanding_p_p', 'uses' => 'ExtractInvoiceController@postExtractFromDatePp']);
                    Route::post('export_results_outstanding_p_p/{from}/{to}', ['as' => 'export_results_outstanding_p_p', 'uses' => 'ExtractInvoiceController@export_results_outstanding_p_p']);

                    Route::get('cpd_members_report', ['as' => 'cpd-members-report', 'uses' => 'ReportController@cpd_members']);
                    Route::post('cpd_members_report', ['as' => 'cpd-members-report', 'uses' => 'ReportController@export_cpd_members']);
                });

                Route::group(['prefix' => 'sales', 'middleware' => ['role:super', 'permission:view-reports'], 'as' => 'sales.', ''], function() {
                    Route::get('agent_report', ['as' => 'agent_report', 'uses' => 'ReportController@agent_report']);
                    Route::post('agent_report', ['as' => 'agent_report', 'uses' => 'ReportController@export_agent_report']);
                });

                Route::group(['prefix' => 'events', 'as' => 'events.', ''], function() {
                    // Events Stats extract
                    Route::get('stat/extract', ['as' => 'stat.extract', 'uses' => 'ExtractEventController@get_extract']);
                    Route::post('stat/extract', ['as' => 'stat.post_extract', 'uses' => 'ExtractEventController@post_extract']);
                });
            });

            // Payment Report Routes

            Route::get('payments/view/payments_per_day', ['as' => 'admin.payments.payments_per_day', 'uses' => 'ViewPaymentsController@show_payments_per_day']);
            Route::post('payments/view/payments_per_day', ['as' => 'admin.payments.payments_per_day', 'uses' => 'ViewPaymentsController@get_payments_per_day']);
            Route::get('payments/view/payments_per_day/export/{from}/{to}/{type?}', ['as' => 'admin.payments.export', 'uses' => 'ViewPaymentsController@export']);

            Route::get('payments/view/summary', ['as' => 'admin.payments.payments_per_day.summary', 'uses' => 'ViewPaymentsController@summary']);
            Route::post('payments/view/summary', ['as' => 'admin.payments.payments_per_day.post_summary', 'uses' => 'ViewPaymentsController@postsummary']);

            Route::get('wallet/transactions', ['as' => 'admin.wallet_transactions', 'uses' => 'AdminWalletTransactionController@index']);
            Route::post('wallet/transactions', ['as' => 'admin.wallet_transactions', 'uses' => 'AdminWalletTransactionController@export']);
        });

        // Subscriptions
        Route::post('members/subscription/upgrade', 'SubscriptionController@upgradeSubscription');

        // Webinar Links Controller
        Route::get('webinars', ['as' => 'admin.webinars', 'uses' => 'AdminWebinarController@index']);
        Route::get('webinars_files', ['as' => 'admin.event_files', 'uses' => 'AdminWebinarController@webinarFiles']);

        Route::group(['middleware' => 'permission:can-manage-faq-section'], function (){
            Route::group(['prefix' => 'faq', 'as' => 'faq.'], function()
            {
                Route::get('categories/link', ['as' => 'categories.link', 'uses' => 'FaqCategoriesContorller@link']);
                Route::post('categories/link', ['as' => 'categories.link', 'uses' => 'FaqCategoriesContorller@link_store']);
                Route::get('categories/unlink/{id}', ['as' => 'categories.unlink', 'uses' => 'FaqCategoriesContorller@unlink']);
                Route::get('categories', ['as' => 'categories', 'uses' => 'FaqQuestionController@getFaqCategories']);
                
                Route::get('all', ['as' => 'all', 'uses' => 'FaqQuestionController@getHome']);
                Route::get('tags', ['as' => 'tags', 'uses' => 'FaqTagController@getAdminIndex']);
                Route::post('tags', ['as' => 'tags_store', 'uses' => 'FaqTagController@store']);
                Route::patch('tags/{id}', ['as' => 'tags_patch', 'uses' => 'FaqTagController@update']);
                Route::get('tags/{id}', ['as' => 'tags_destroy', 'uses' => 'FaqTagController@destroy']);

                route::get('questions', ['as' => 'questions', 'uses' =>  'FaqQuestionController@index']);
                route::post('questions', ['as' => 'questions_new', 'uses' =>  'FaqQuestionController@store']);
                route::get('questions/edit/{id}', ['as' => 'questions_edit', 'uses' =>  'FaqQuestionController@edit']);
                route::patch('questions/edit/{id}', ['as' => 'questions_edit', 'uses' =>  'FaqQuestionController@update']);
                route::get('questions/{id}/remove', ['as' => 'questions_remove', 'uses' =>  'FaqQuestionController@destroy']);
            });
        });

        // Events Gallery Admin
        Route::get('gallery/upload/{folder}', 'GalleryUploadController@upload');
        Route::post('gallery/save/upload/{folder}', 'GalleryUploadController@uploadFiles');
        Route::resource('gallery', 'GalleryUploadController');
        Route::resource('folders', 'GalleryFolderController');

        Route::group(['prefix' => 'reps', 'as' => 'admin.reps.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'RepController@index']);
            Route::get('create', ['as' => 'create', 'uses' => 'RepController@create']);
            Route::post('assign/{member}', ['as' => 'assign', 'uses' => 'RepController@assign']);
            Route::post('rep/update/{id}', ['as' => 'update', 'uses' => 'RepController@update']);
            Route::post('save', ['as' => 'save', 'uses' => 'RepController@store']);
            Route::post('destroy/{id}', ['as' => 'destroy', 'uses' => 'RepController@destroy']);
        });

        // Agent groups
        Route::group(['prefix' => 'agent_groups', 'as' => 'admin.agent_groups.', 'middleware' => 'permission:access-admin-plan-features'], function (){
            Route::get('/', ['as' => 'index', 'uses' => 'AdminAgentGroupController@index']);
            Route::get('create', ['as' => 'create', 'uses' => 'AdminAgentGroupController@create']);
            Route::post('create', ['as' => 'store', 'uses' => 'AdminAgentGroupController@store']);
            Route::get('edit/{group}', ['as' => 'edit', 'uses' => 'AdminAgentGroupController@edit']);
            Route::post('edit/{group}', ['as' => 'update', 'uses' => 'AdminAgentGroupController@update']);
            Route::delete('destroy/{group}', ['as' => 'destroy', 'uses' => 'AdminAgentGroupController@destroy']);
        });

    });

    // Donations 
    Route::group(['prefix' => 'admin/donations', 'as' => 'admin.donations.'], function (){
        Route::get('index', ['as' => 'index', 'uses' => 'Admin\DonationController@index']);
        Route::get('export', ['as' => 'export', 'uses' => 'Admin\DonationController@export']);
    });

    Route::group(['prefix' => 'categories', 'as' => 'admin.categories.'], function () {
        Route::get('index', ['as' => 'index', 'uses' => 'Admin\Blog\CategoryController@index']);
        Route::get('create', ['as' => 'create', 'uses' => 'Admin\Blog\CategoryController@create']);
        Route::post('create', ['as' => 'store', 'uses' => 'Admin\Blog\CategoryController@store']);
        Route::get('edit/{authorId}', ['as' => 'edit', 'uses' => 'Admin\Blog\CategoryController@edit']);
        Route::post('update/{authorId}', ['as' => 'update', 'uses' => 'Admin\Blog\CategoryController@update']);
        Route::post('destroy', ['as' => 'destroy', 'uses' => 'Admin\Blog\CategoryController@destroy']);
    });

    // Leads
    Route::group(['prefix' => 'admin/leads', 'as' => 'admin.leads'], function () {
        
        Route::group(['prefix' => 'status', 'as' => '.status'], function () {
            Route::get('/', ['as' => '.index', 'uses' => 'Admin\LeadStatusController@index']);
            Route::get('create', ['as' => '.create', 'uses' => 'Admin\LeadStatusController@create']);
            Route::post('store', ['as' => '.store', 'uses' => 'Admin\LeadStatusController@store']);
            Route::get('edit/{statusId}', ['as' => '.edit', 'uses' => 'Admin\LeadStatusController@edit']);
            Route::put('update/{statusId}', ['as' => '.update', 'uses' => 'Admin\LeadStatusController@update']);
            Route::delete('destroy/{statusId}', ['as' => '.destroy', 'uses' => 'Admin\LeadStatusController@destroy']);
        });

        Route::get('/', ['as' => '.index', 'uses' => 'Admin\LeadsController@index']);
        Route::get('/{lead_id}/activity', ['as' => '.activity', 'uses' => 'Admin\LeadsController@show']);
        Route::post('/update', ['as' => '.update', 'uses' => 'Admin\LeadsController@update']);
    });

});

Route::group(['prefix' => 'upgrade_subscription', 'as' => 'upgrade_subscription.'], function () {
    Route::post('upgrade', ['as' => 'upgrade', 'uses' => 'upgradeSubscriptionController@upgrade']);
    Route::get('pendingList', ['as' => 'pendinglist', 'uses' => 'upgradeSubscriptionController@pendingList']);
   
    Route::get('approve/{member}', ['as' => 'approve', 'uses' => 'upgradeSubscriptionController@approve']);
    Route::get('decline/{member}', ['as' => 'decline', 'uses' => 'upgradeSubscriptionController@decline']);
});

Route::group(['prefix' => 'news', 'as' => 'admin.news.', 'middleware' => 'role:super|news-editor'], function () {
    Route::get('articles', ['as' => 'index', 'uses' => 'Admin\Blog\PostController@index']);
    Route::get('articles/create', ['as' => 'create', 'uses' => 'Admin\Blog\PostController@create']);
    Route::post('articles/create', ['as' => 'store', 'uses' => 'Admin\Blog\PostController@store']);
    Route::get('articles/edit/{post}', ['as' => 'edit', 'uses' => 'Admin\Blog\PostController@edit']);
    Route::post('articles/update/{post}', ['as' => 'update', 'uses' => 'Admin\Blog\PostController@update']);
    Route::get('articles/upoadToSendinBlue/{post}', ['as' => 'upload.sendinBlue', 'uses' => 'Admin\Blog\PostController@uploadToSendingBlue']);
});

Route::group(['prefix' => 'authors', 'as' => 'admin.authors.'], function () {
    Route::get('index', ['as' => 'index', 'uses' => 'Admin\Blog\AuthorController@index']);
    Route::get('create', ['as' => 'create', 'uses' => 'Admin\Blog\AuthorController@create']);
    Route::post('create', ['as' => 'store', 'uses' => 'Admin\Blog\AuthorController@store']);
    Route::get('edit/{authorId}', ['as' => 'edit', 'uses' => 'Admin\Blog\AuthorController@edit']);
    Route::post('update/{authorId}', ['as' => 'update', 'uses' => 'Admin\Blog\AuthorController@update']);
    Route::post('destroy', ['as' => 'destroy', 'uses' => 'Admin\Blog\AuthorController@destroy']);
});

Route::group(['prefix' => 'comments', 'as' => 'admin.post.comments.'], function () {
    Route::get('comments/post/{post}', ['as' => 'index', 'uses' => 'Admin\Blog\CommentsController@index']);
    Route::get('/{comment}/comment/approve', ['as' => 'approve', 'uses' => 'Admin\Blog\CommentsController@update']);
    Route::get('/{comment}/comment/decline', ['as' => 'decline', 'uses' => 'Admin\Blog\CommentsController@decline']);
});

Route::group(['prefix' => 'resource_centre', 'as' => 'admin.resource_centre.'], function () {
    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'Admin\ResourceCentreCategoryController@index']);
        Route::get('create', ['as' => 'create', 'uses' => 'Admin\ResourceCentreCategoryController@create']);
        Route::post('save', ['as' => 'save', 'uses' => 'Admin\ResourceCentreCategoryController@store']);
        Route::post('update', ['as' => 'update', 'uses' => 'Admin\ResourceCentreCategoryController@update']);
    });

    Route::group(['prefix' => 'sub_categories', 'as' => 'sub_categories.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'Admin\ResourceCentreSubCategoryController@index']);
        Route::get('create', ['as' => 'create', 'uses' => 'Admin\ResourceCentreSubCategoryController@create']);
        Route::post('save', ['as' => 'save', 'uses' => 'Admin\ResourceCentreSubCategoryController@store']);
        Route::post('update', ['as' => 'update', 'uses' => 'Admin\ResourceCentreSubCategoryController@update']);
    });

    Route::group(['prefix' => 'tickets', 'as' => 'tickets.', 'middleware' => 'permission:access-admin-section'], function (){
        route::get('/', ['as' => 'index', 'uses' => 'Admin\ResourceCentreTicketController@index']);
        Route::get('get_threads', ['as' => 'get_threads', 'uses' => 'Admin\ResourceCentreTicketController@get_threads']);
        Route::get('thread/show/{TicketId}', ['as' => 'show', 'uses' => 'Admin\ResourceCentreTicketController@show']);
        Route::post('thread/show/{TicketId}', ['as' => 'assign', 'uses' => 'Admin\ResourceCentreTicketController@assign']);
        Route::post('thread/update', ['as' => 'update', 'uses' => 'Admin\ResourceCentreTicketController@update']);
    });
});

Route::group(['prefix' => 'admin/courses', 'as' => 'admin.courses.'], function (){
    Route::get('/', ['as' => 'index' , 'uses' => 'Admin\CourseController@index']);
    Route::get('list', ['as' => 'list' , 'uses' => 'Admin\CourseController@list_courses']);
    Route::get('create', ['as' => 'create' , 'uses' => 'Admin\CourseController@create']);
    Route::post('store', ['as' => 'store' , 'uses' => 'Admin\CourseController@store']);
    Route::get('show/{course}', ['as' => 'show' , 'uses' => 'Admin\CourseController@show']);
    Route::get('duplicate/{course}', ['as' => 'duplicate' , 'uses' => 'Admin\CourseController@duplicate']);
    Route::post('update/{course}', ['as' => 'update' , 'uses' => 'Admin\CourseController@update']);

    Route::get('{courseId}/add_moodle', ['as' => 'add_moodle' , 'uses' => 'Admin\CourseController@create_moodle_course']);
    
    Route::group(['prefix' => 'admin/links', 'as' => 'links.'], function (){
        Route::post('store/{courseId}', ['as' => 'store' , 'uses' => 'Admin\CourseLinkController@store']);
        Route::post('update/{courseId}', ['as' => 'update' , 'uses' => 'Admin\CourseLinkController@update']);
    });

    Route::get('students/{courseId}', ['as' => 'students' , 'uses' => 'Admin\CourseController@list_students']);
    Route::get('invoices/{courseId}', ['as' => 'invoices' , 'uses' => 'Admin\CourseController@list_invoices']);
});




Route::group(['prefix' => 'admin/rewards', 'as' => 'admin.rewards.'], function (){
    Route::get('/', ['as' => 'index' , 'uses' => 'Admin\RewardsController@index']);
    Route::get('list', ['as' => 'list' , 'uses' => 'Admin\RewardsController@list_reward']);
    Route::get('create', ['as' => 'create' , 'uses' => 'Admin\RewardsController@create']);
    Route::post('store', ['as' => 'store' , 'uses' => 'Admin\RewardsController@store']);
    Route::get('show/{course}', ['as' => 'show' , 'uses' => 'Admin\RewardsController@show']);
    Route::post('update/{course}', ['as' => 'update' , 'uses' => 'Admin\RewardsController@update']);
    Route::get('report/extract', ['as' => 'report' , 'uses' => 'Admin\RewardsController@reward_export_export']);
});

Route::group(['prefix' => 'admin/sponsor', 'as' => 'admin.sponsor.'], function (){
    Route::get('/', ['as' => 'index' , 'uses' => 'Admin\SponsorListController@index']);
    Route::get('list', ['as' => 'list' , 'uses' => 'Admin\SponsorListController@list_sponsor']);
    Route::get('create', ['as' => 'create' , 'uses' => 'Admin\SponsorListController@create']);
    Route::post('store', ['as' => 'store' , 'uses' => 'Admin\SponsorListController@store']);
    Route::get('show/{course}', ['as' => 'show' , 'uses' => 'Admin\SponsorListController@show']);
    Route::post('update/{course}', ['as' => 'update' , 'uses' => 'Admin\SponsorListController@update']);

});

Route::group(['prefix' => 'admin/industries', 'as' => 'admin.industries.'], function (){
    Route::get('/', ['as' => 'index' , 'uses' => 'Admin\IndustryController@index']);
    Route::get('list', ['as' => 'list' , 'uses' => 'Admin\IndustryController@list_industries']);
    Route::get('create', ['as' => 'create' , 'uses' => 'Admin\IndustryController@create']);
    Route::post('store', ['as' => 'store' , 'uses' => 'Admin\IndustryController@store']);
    Route::get('show/{industry}', ['as' => 'show' , 'uses' => 'Admin\IndustryController@show']);
    Route::post('update/{industry}', ['as' => 'update' , 'uses' => 'Admin\IndustryController@update']);
    // Route::get('report/extract', ['as' => 'report' , 'uses' => 'Admin\IndustryController@reward_export_export']);
});

Route::group(['prefix' => 'admin/unsubscribers', 'as' => 'admin.unsubscribers.'], function (){
    Route::get('/', ['as' => 'index' , 'uses' => 'Admin\Unsubscribe\UnsubscribeController@index']);
    Route::get('list', ['as' => 'list' , 'uses' => 'Admin\Unsubscribe\UnsubscribeController@list_unsubscribers']);
    Route::get('report/extract', ['as' => 'report' , 'uses' => 'Admin\Unsubscribe\UnsubscribeController@export_report']);
});

Route::group(['prefix' => 'admin/resubscribers', 'as' => 'admin.resubscribers.'], function (){
    Route::get('/', ['as' => 'index' , 'uses' => 'Admin\Unsubscribe\UnsubscribeController@resubscriber']);
    Route::get('list', ['as' => 'list' , 'uses' => 'Admin\Unsubscribe\UnsubscribeController@list_resubscribers']);
    Route::get('report/extract', ['as' => 'report' , 'uses' => 'Admin\Unsubscribe\UnsubscribeController@export_resubscriber_report']);
});

Route::group(['prefix' => 'admin/popular', 'as' => 'admin.popular.'], function (){
    Route::get('/', ['as' => 'index' , 'uses' => 'Admin\PopularProductsController@index']);
});

Route::group(['prefix' => 'admin/chat', 'as' => 'admin.'], function () {
    Route::get('/', ['as' => 'chat' , 'uses' => 'Admin\ChatController@index']);
    Route::get('/list', ['as' => 'chat.list' , 'uses' => 'Admin\ChatController@getChatList']);
    Route::get('/history', ['as' => 'chat.history' , 'uses' => 'Admin\ChatController@getChatHistory']);
    Route::get('/chatbox/{roomId}', ['as' => 'chatbox' , 'uses' => 'Admin\ChatController@clientChat']);
    Route::get('/chatbox/{roomId}/read', ['as' => 'chatbox.read' , 'uses' => 'Admin\ChatController@readClientChat']);
    Route::get('/messages', ['as' => 'chat.messages' , 'uses' => 'Admin\ChatController@listMessages']);
    Route::post('/message/{roomId}', ['as' => 'chat.message' , 'uses' => 'Admin\ChatController@saveMessage']);
});