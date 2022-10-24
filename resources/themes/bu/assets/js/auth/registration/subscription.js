Vue.component('app-subscription-register-screen', {
    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        this.getPlans()
    },


    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            plans: [],
            selectedPlan: null,
            selectedFeatures: [],
            planTypeState: true,
            profession: [],
            customFeatures: [
                'compliance-and-legislation-update',
                'digital-branding',
                'xbrl'
            ],
            forms: {
                registration: $.extend(true, new AppForm({
                    busy: false,
                    first_name: '',
                    last_name: '',
                    id_number: '',
                    cell: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    plan: '',
                    terms: false,
                    pi_cover: false,
                    features: []
                }), App.forms.registration)
            }
        };
    },


    computed: {

        /**
         * Return the count of selected features
         */
        countSelectedFeatures() {
            return this.selectedFeatures.length
        },

        /**
         * Check if we can select more features
         */
        canSelectMoreFeatures() {
            return this.countSelectedFeatures < 8
        },

        /*
         * Compute the user's full name
         */
        fullName: function () {
            return this.forms.registration.first_name + ' ' + this.forms.registration.last_name
        },

        /*
         * Determine if the plans have been loaded from the API.
         */
        plansAreLoaded: function () {
            return this.plans.length > 0
        },


        /*
         * Get all of the "default" available plans. Typically this is monthly.
         */
        defaultPlans: function () {
            if (this.monthlyPlans.length > 0) {
                return this.monthlyPlans
            }

            if (this.yearlyPlans.length > 0) {
                return this.yearlyPlans
            }
        },


        /*
         * Get all of the plans that have a monthly interval.
         */
        monthlyPlans: function () {
            return _.filter(this.plans, function (plan) {
                return plan.interval == 'month' && plan.inactive != 1
            });
        },


        /*
         * Get all of the plans that have a yearly interval.
         */
        yearlyPlans: function () {
            return _.filter(this.plans, function (plan) {
                return plan.interval == 'year' && plan.inactive != 1
            });
        },


        /*
         * Determine if a free plan is currently selected during registration.
         */
        freePlanIsSelected: function () {
            return this.selectedPlan && this.selectedPlan.price <= 0
        },


        /*
         * Determine if the plan type selector is set to "monthly".
         */
        shouldShowDefaultPlans: function () {
            return !this.planTypeState
        },


        /*
         * Determine if the plan type selector is set to "yearly".
         */
        shouldShowYearlyPlans: function () {
            return this.planTypeState
        },


        /*
         * Get the full selected plan price with currency symbol.
         */
        selectedPlanPrice: function () {
            if (this.selectedPlan) {
                return 'R ' + this.selectedPlan.price
            }
        },

        /**
         * Check if Monthly Plan is selected
         */
        selectedPlanIsMonthly: function () {
            if (this.selectedPlan.interval == 'month') {
                return true
            }
        },

        /**
         * Check if Yearly Plan is selected
         */
        selectedPlanIsYearly: function () {
            if (this.selectedPlan.interval == 'year') {
                return true
            }
        }
    },


    methods: {
        /*
         * Get all of the Apps plans from the API.
         */
        getPlans: function () {
            this.$http.get('/api/subscriptions/plans')
                .success(function (plans) {
                    var self = this

                    this.plans = _.filter(plans, function (plan) {
                        return plan
                    })

                    var queryString = URI(document.URL).query(true)

                    // If there is only one plan, automatically select it...
                    if (this.plans.length == 1) {
                        this.setSelectedPlan(this.plans[0]);
                        setTimeout(function () {
                            $('.app-first-field').filter(':visible:first').focus()
                        }, 100)
                    }
                });
        },


        /*
         * Select a plan from the list. Initialize registration form.
         */
        selectPlan: function (plan) {
            this.setSelectedPlan(plan);

            setTimeout(function () {
                $('.app-first-field')
                    .filter(':visible:first')
                    .focus();
            }, 100);
        },


        /*
         * Set the selected plan.
         */
        setSelectedPlan: function (plan) {
            this.selectedPlan = plan;
            this.forms.registration.plan = plan.id;
        },


        /*
         * Clear the selected plan from the component state.
         */
        selectAnotherPlan: function () {
            this.selectedPlan = null
            this.clearSelectedFeatures()
            this.forms.registration.plan = ''
            this.forms.registration.paymentOption = null
            this.forms.registration.card = {}
            this.forms.registration.debit = {}
        },


        /**
         * Clear User Selected Features
         */
        clearSelectedFeatures() {
            this.selectedFeatures = [];
        },


        /*
         * Initialize the registration process.
         */
        register: function () {

        }
    }
});
