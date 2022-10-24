/*
 * Load Vue & Vue-Resource.
 *
 * Vue is the JavaScript framework used by our App.
 */
if (window.Vue === undefined) {
    window.Vue = require('vue');

    /**
     * Disable Vue Debug tools
     */
    Vue.config.debug = false;
	Vue.config.devtools = false;
}

require('vue-resource');

Vue.http.headers.common['X-CSRF-TOKEN'] = App.csrfToken;

/**
 * Load Promises library.
 */
window.Promise = require('promise');

/*
 * Load Underscore.js, used for map / reduce on arrays.
 */
if (window._ === undefined) {
    window._ = require('underscore');
}

/*
 * Load Moment.js, used for date formatting and presentation.
 */
if (window.moment === undefined) {
    window.moment = require('moment');
}

/*
 * Load Sweetalert.
 */
if (window.swal === undefined) {
    window.swal = require('sweetalert');
}

/*
 * Load Pusher.
 */
if (window.Pusher === undefined) {
    window.Pusher = require('pusher-js');
}

/*
 * Load jQuery and Bootstrap jQuery, used for front-end interaction.
 */
if (window.$ === undefined || window.jQuery === undefined) {
    window.$ = window.jQuery = require('jquery');
}

/*
 * Load Datepicker, used for front-end interaction.
 */
if (window.daterangepicker === undefined) {
    window.daterangepicker = require('bootstrap-daterangepicker');
}

/**
 * Define the App component extension points.
 */
//App.components = {
//    profileBasics: {},
//    teamOwnerBasics: {},
//    editTeamMember: {},
//    navDropdown: {}
//};

/**
 * Load the App form utilities.
 */
require('./../forms/bootstrap');
