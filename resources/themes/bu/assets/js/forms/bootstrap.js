/**
 * Initialize the App form extension points.
 */
App.forms = {
    registration: {},
    eventSignup: {},
    incomeReports: {},
    exportReport: {}
};

/**
 * Load the AppForm helper class.
 */
require('./instance');

/**
 * Define the form error collection class.
 */
require('./errors');

/**
 * Add additional form helpers to the App object.
 */
$.extend(App, require('./http'));

/**
 * Define the App form input components.
 */
require('./components');
