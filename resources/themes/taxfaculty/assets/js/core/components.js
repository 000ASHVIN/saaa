/**
 * Load all of the core Vue components.
 */
require('./../billing/index');
require('./../insurance/Insurance');
require('./../common/errors');
require('./../common/filters');
require('./../auth/registration/simple');
require('./../auth/registration/subscription');
require('./../store/listing');
require('./../store/checkout');
require('./../events/single/single');
require('./../events/admin/all-data-grid');
require('./../events/admin/single-data-grid');
require('./../events/admin/assign-to-plans');
require('./../videos/admin/select-event-and-venue');
require('./../videos/admin/create-edit-video');
require('./../cpd/admin/assign');
require('./../store/listings/admin/add-products');
require('./../store/products/admin/index');
require('./../store/orders/admin/index');
require('./../assessments/admin/create-edit');
require('./../assessments/frontend/assessment');
require('./../stats/admin/members/members-stats');
require('./../stats/admin/members/orders-stats');
require('./../stats/admin/members/registrations-stats');
require('./../stats/admin/members/installments-stats');
require('./../invoices/dashboard/pay');
require('./../imports/members-register-for-event');
require('./../subscriptions/subscriptions');
require('./../subscriptions/single_subscriptions');
require('./../subscriptions/plans');
require('./../subscriptions/donate');
require('./../webinar_on_demand/checkout');
require('./../debit_orders/debit');

require('./../reports/income');
require('laravel-vue-pagination');

require('./../orders/order');
require('./../orders/pay');
require('./../comments/index');
require('./../auth/login/login');
require('./../auth/login/auth');
require('./../course/enroll');

require('./../support_ticket/support_ticket');

require('./../orders/resource_search');