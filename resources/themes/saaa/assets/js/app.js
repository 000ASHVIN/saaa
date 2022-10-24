/*
 * Load the components.
 */
require('./core/bootstrap');
require('./core/components');

window.Vue.config.productionTip = false
window.Vue.config.devtools = true
/**
 * Export the application.
 */
new Vue({
    el: '#app',

    /*
     * Bootstrap the application. Load the initial data.
     */
    ready: function () {
        if (App.userId) {
            this.getUser();
        }

        if (App.currentTeamId) {
            this.getTeams();
            this.getCurrentTeam();
        }

        this.whenReady();
    },


    events: {
        /**
         * Handle requests to update the current user from a child component.
         */
        updateUser: function () {
            this.getUser();

            return true;
        },


        /**
         * Handle requests to update the teams from a child component.
         */
        updateTeams: function () {
            this.getTeams();

            return true;
        }
    },


    methods: {
        /**
         * This method would be overridden by developer.
         */
        whenReady: function () {
            // $('.is-date').daterangepicker({
            //     singleDatePicker: true,
            //     showDropdowns: true,
            //     locale: {
            //         format: 'YYYY-MM-DD'
            //     },
            // });
        },


        /**
         * Retrieve the user from the API and broadcast it to children.
         */
        getUser: function () {
            this.$http.get('/api/users/me')
                .success(function(user) {
                    this.$broadcast('userRetrieved', user);
                });
        },

        /*
         * Get all of the user's current teams from the API.
         */
        getTeams: function () {
            this.$http.get('/api/teams')
                .success(function (teams) {
                    this.$broadcast('teamsRetrieved', teams);
                });
        },


        /*
         * Get the user's current team from the API.
         */
        getCurrentTeam: function () {
            this.$http.get('/api/teams/' + App.currentTeamId)
                .success(function (team) {
                    this.$broadcast('currentTeamRetrieved', team);
                });
        }
    }
});