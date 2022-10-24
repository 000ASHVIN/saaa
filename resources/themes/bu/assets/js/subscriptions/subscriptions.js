import moment from 'moment';
Vue.component('app-subscriptions-screen', {

    props: {
        plans: null,
        user: null,
        profession: null,
        upgrade: '',
    },

    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            selectedPlan: null,
            selectedFeatures: [],
            customFeatures: [],
            instantlink: '',
            payment_token: '',
            instant_eft_success: false,
            planTypeState: false,
            otp: null,
            waiting_on_otp: false,
            requested_otp: false,
            forms: {
                subscription: $.extend(true, new AppForm({
                    busy: false,
                    bf: false,
                    plan: '',
                    terms: false,
                    paymentOption: 'cc',
                    pi_cover:false,
                    features: [],
                    card: null,
                    debit: {
                        bank: '',
                        account_number: '',
                        account_type: '',
                        branch_name: '',
                        branch_code: '',
                        billable_date: '',
                        account_holder: '',
                        id_number: '',
                        registration_number: "",
                        type_of_account: "",
                        otp: ""
                    }
                }), App.forms.subscription)
            },
            addingNewCard: false,
            newcard: {
                number: '',
                holder: '',
                exp_month: '',
                exp_year: '',
                cvv: '',
                return: '',
                errors: []
            },
            threeDs: {
                url: '',
                connector: '',
                MD: '',
                TermUrl: '',
                PaReq: ''
            },
            readyForThreeDs: false
        };
    },


    ready() {
        this.getConfig();

        var queryString = URI(document.URL).query(true);        

        window.addEventListener("message", (event) => {
            if (typeof event.data == 'string') {
                try {
                    eval(event.data);
                    setTimeout(() => {
                        this.forms.subscription.busy = false;
                        swal.close()
                    }, 2000)
                }
                catch(e){
                    this.forms.subscription.busy = false;
                    // swal.close()
                }
            }
        });

        // if(queryString.plan) {
        //     var plan = _.find(this.plans, (plan) => {
        //         return plan.id == queryString.plan
        //     });
        //
        //     this.setSelectedPlan(plan)
        // }
    },


    computed: {

        pastDebitDateSelected() {
            if(! this.forms.subscription.debit.billable_date) {
                return false;
            }

            if(moment().date() >= this.forms.subscription.debit.billable_date) {
                return true;
            }
        },

        otpCorrect() {
            if(this.otp == null)
                return false;

            return this.otp == this.forms.subscription.debit.otp;
        },

        /**
         * Return the count of selected features
         */
        countSelectedFeatures() {
            return this.selectedFeatures.length;
        },

        /**
         * Check if we can select more features
         */
        canSelectMoreFeatures() {
            if(this.countSelectedFeatures < 8){
                return true;
            }else{
                swal({
                    type: 'success',
                    title: 'Good job!',
                    text: "You have selected 8 Topics, Please proceed or clear your selection to reselect."
                })
                return false;
            }
        },

        /*
         * Determine if the plans have been loaded from the API.
         */
        plansAreLoaded: function () {
            return this.plans.length >= 0;
        },

        /*
         * Get all of the "default" available plans. Typically this is monthly.
         */
        defaultPlans: function () {
            if(this.plans.length >= 1){
                if (this.monthlyPlans.length > 0) {
                    return this.monthlyPlans;
                }

                if (this.yearlyPlans.length > 0) {
                    return this.yearlyPlans;
                }
            }else{
                return this.plans[1]
            }
        },


        /*
         * Get all of the plans that have a monthly interval.
         */
        monthlyPlans: function () {
            return _.filter(this.plans, function (plan) {
                return plan.interval == 'month' && plan.inactive != 1;
            });
        },


        /*
         * Get all of the plans that have a yearly interval.
         */
        yearlyPlans: function () {
            return _.filter(this.plans, function (plan) {
                return plan.interval == 'year' && plan.inactive != 1;
            });
        },


        /*
         * Determine if a free plan is currently selected during registration.
         */
        freePlanIsSelected: function () {
            return this.selectedPlan && this.selectedPlan.price <= 0;
        },


        /*
         * Determine if the plan type selector is set to "monthly".
         */
        shouldShowDefaultPlans: function () {
            return !this.planTypeState;
        },


        /*
         * Determine if the plan type selector is set to "yearly".
         */
        shouldShowYearlyPlans: function () {
            return this.planTypeState;
        },


        /*
         * Get the full selected plan price with currency symbol.
         */
        selectedPlanPrice: function () {
            if (this.selectedPlan) {
                if (this.forms.subscription.bf){
                    return 'R ' + this.selectedPlan.bf_price;
                } else{
                    return 'R ' + this.selectedPlan.price;
                }
            }
        },

        selectedPlanIsMonthly: function () {
            if (this.selectedPlan.interval == 'month') {
                return true;
            }
        },

        selectedPlanIsYearly: function () {
            if (this.selectedPlan.interval == 'year') {
                return true;
            }
        },

        debitOrderDetailsCompleted() {
            if (this.forms.subscription.debit.type_of_account === 'company'){
                return this.forms.subscription.debit.bank != '' &&
                    this.forms.subscription.debit.account_number != '' &&
                    this.forms.subscription.debit.account_type != '' &&
                    this.forms.subscription.debit.branch_code != '' &&
                    this.forms.subscription.debit.branch_name != '' &&
                    this.forms.subscription.debit.billable_date != '' &&
                    this.forms.subscription.debit.account_holder != '' &&
                    this.forms.subscription.debit.type_of_account != '' &&
                this.forms.subscription.debit.registration_number != ''
            }else {
                return this.forms.subscription.debit.bank != '' &&
                    this.forms.subscription.debit.account_number != '' &&
                    this.forms.subscription.debit.account_type != '' &&
                    this.forms.subscription.debit.branch_code != '' &&
                    this.forms.subscription.debit.branch_name != '' &&
                    this.forms.subscription.debit.billable_date != '' &&
                    this.forms.subscription.debit.account_holder != '' &&
                    this.forms.subscription.debit.type_of_account != '' &&
                    this.forms.subscription.debit.id_number != ''
           }
        }
    },


    methods: {
        getConfig() {
            this.$http.get('/api/getMyCustomConfig')
                .then(response => {
                    this.customFeatures = response.data;
                })
        },

        popEft() {
            this.process();
            this.forms.subscription.busy = true;
            this.processEft();
        },

        addCard() {
            this.newcard.return = `/`+ window.location.pathname + `?threeDs=yes&plan=${this.forms.subscription.plan}`;
            this.processPayment();

            this.$http.post('/account/billing', this.newcard)
                .then(response => {
                    if(response.data.redirect === undefined || response.data.redirect === null) {
                        this.saveCard(response.data.id);
                        this.addingNewCard = false;
                    } else {
                        this.threeDs.url = response.data.redirect.url;
                        for (var i = response.data.redirect.parameters.length - 1; i >= 0; i--) {
                            this.threeDs[response.data.redirect.parameters[i].name] = response.data.redirect.parameters[i].value;
                        }
                        this.readyForThreeDs = true;
                        swal.close();
                    }
                })
                .catch(errors => {
                    this.newcard.errors = errors.data;
                    swal.close();
                });
        },

        /**
         * Save User Card
         */
        saveCard(id) {
            this.$http.post('/account/billing/card', { id: id })
                .then(response => {
                    this.user.cards.push(response.data.card)
                    this.forms.subscription.card = response.data.card.id;
                    swal.close()
                })
                .catch(errors => {
                    if(errors.data.number) {
                        swal({
                            type: 'error',
                            title: 'Whoops',
                            text: errors.data.number
                        })
                    }
                })
        },
        processPayment: function() {
            swal({
                type: "warning",
                title: "Payment Processing",
                text: "Please wait while we are processing your payment.Do not Refresh page or close browser"
            })
        },
        /*
         * Select a plan from the list. Initialize registration form.
         */
        selectPlan: function (plan) {
            this.setSelectedPlan(plan);

            if (this.plans.length >= 2){
                window.scrollTo({
                    top: 450,
                    behavior: "smooth"
                });
            }

            setTimeout(() => {
                this.getEftLink();
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
            this.forms.subscription.plan = plan.id;
        },


        /*
         * Clear the selected plan from the component state.
         */
        selectAnotherPlan: function () {
            this.cancelPayment();
            this.selectedPlan = null;
            this.clearSelectedFeatures();
            this.forms.subscription.plan = '';
            this.forms.subscription.paymentOption = 'cc';
            this.forms.subscription.card = null;
            this.forms.subscription.debit = {};
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

            if(this.selectedPlan.is_custom) {
                if(this.selectedFeatures.length < 8) {
                    swal({
                        type: 'warning',
                        title: "Select more topics",
                        text: "You have only selected (" + this.selectedFeatures.length + "/8) required Topics for your subscription package."
                    });
                    return;
                }
            }

            this.forms.subscription.errors.forget();

            this.forms.subscription.busy = true;
            //this.process();
            this.sendRegistration();

        },

        getEftLink: function () {
            this.$http.post('/instant-eft', { plan: this.selectedPlan.id, bf: this.forms.subscription.bf })
                .success((response) => {
                    this.instantlink = response.link
                    this.payment_token = response.key
                });
        },

        processEft: function () {
            var paymentKey = this.payment_token;
            var paymentType = 'eft';

            eftSec.checkout.settings.serviceUrl = '{protocol}://eft.ppay.io/rpp-transaction/create-from-key';
            eftSec.checkout.settings.checkoutRedirect = false;
            eftSec.checkout.settings.onComplete = (data) => {
                eftSec.checkout.hideFrame();
                this.process();
                console.log(data) 
                if(data.success) {
                    this.instant_eft_success = true;
                    this.sendRegistration();
                } else {
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: "Your Payment has failed, please try again"
                    })
                }

            };
            eftSec.checkout.init({
                paymentKey: paymentKey,
                paymentType : paymentType,
                onLoad: function() {
                    swal.close();
                }
            });
        },

        cancelPayment: function () {
            this.forms.subscription.busy = false;
        },

        /*
         * After obtaining the 3DS Information, send the registration to App.
         */
        sendRegistration: function () {

            if(this.forms.subscription.paymentOption == 'eft' && this.instant_eft_success == false) {
                this.startListener();
                this.forms.subscription.busy = true;
                swal({
                    type: 'warning',
                    title: 'Waiting on Payment',
                    text: "We're waiting for payment confirmation, please do not close this page."
                })
                return;
            }

            if(this.forms.subscription.paymentOption == 'cc' && ! this.forms.subscription.card) {
                swal({
                    type: 'warning',
                    title: 'Error',
                    text: "Unable to register without credit card, please select credit card"
                })
                this.forms.subscription.busy = false;
                return;
            }

            if(this.forms.subscription.paymentOption == 'debit' && ! this.debitOrderDetailsCompleted) {
                swal({
                    type: 'warning',
                    title: 'Error',
                    text: "Debit order details not completed"
                })
                this.forms.subscription.busy = false;
                return;
            }

            if(this.forms.subscription.paymentOption == 'debit' && ! this.requested_otp) {
                let data = {
                    account_number: this.forms.subscription.debit.account_number,
                    branch_code: this.forms.subscription.debit.branch_code
                }

                let valid = false;

                this.$http.post('/peach/validate', data)
                    .then((response) => {
                        if(response.data.Result != 'Valid') {
                            swal({
                                type: 'error',
                                title: 'Invalid Account Details',
                                text: "Ensure that you have filled out your information correctly and try again."
                            })
                            return;
                        } else {
                            valid = true;
                            return this.requestOTP();
                        }
                    })
                    .catch((errors) => {
                        console.log(errors)
                    })

                    if(! valid) {
                        this.forms.subscription.busy = false;
                        return;
                    }
            }

            if(this.forms.subscription.paymentOption == 'debit' && ! this.otpCorrect) {
                swal({
                    type: 'error',
                    title: 'Invalid OTP',
                    text: "The OTP number you have entered is invalid, please try again."
                })
                this.forms.subscription.busy = false;
                return;
            }

            if (this.selectedFeatures.length > 0 && this.selectedPlan.is_custom) {
                this.selectedFeatures.push(this.customFeatures);
                this.forms.subscription.features = this.selectedFeatures;
            }

            this.processPayment();

            this.$http.post('/subscriptions', this.forms.subscription)
                .success((response) => {
                    this.forms.subscription.busy = false;
                    swal.close();                    
                    var redirectUrl = '/dashboard/general';

                    if(this.forms.subscription.pi_cover) {
                        redirectUrl = '/insurance'
                    }
                    window.location = redirectUrl;
                })
                .error((errors) => {
                    swal.close();                    
                    this.selectedFeatures.$remove(this.customFeatures);
                    this.forms.subscription.busy = false;
                    this.forms.subscription.errors.set(errors);
                });
        },

        requestOTP: function () {
            this.$http.post('/peach/otp')
                .then((response) => {
                    this.otp = response.data.otp
                    this.requested_otp = true;
                    this.waiting_on_otp = true;
                    swal.close();
                })
        },

        resendOtp: function () {
            this.$http.post('/peach/otp')
                .then((response) => {
                    this.otp = response.data.otp
                    swal({
                        type: 'success',
                        title: 'OTP Sent!',
                        text: "The OTP has been sent!."
                    })
                })
        },

        startListener: function () {
            var pusher = new Pusher('5e9e56a5a0ebaf5484b0', {
              cluster: 'mt1'
            });

            var channel = pusher.subscribe('instantefts');

            channel.bind("App\\Events\\InstantEftNotificationReceived", (data) => {
              if(this.payment_token == data.payment_key) {
                if(data.success === true) {
                    this.process();
                    this.instant_eft_success = true;
                    this.sendRegistration();
                } else {
                    this.cancelPayment();
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: "Your Payment has failed, please try again"
                    })
                }
              }
            });

        },

        /*
         * Get the proper column width based on the number of plans.
         */
        getPlanColumnWidth: function (count) {
            switch (count) {
                case 1:
                    return 'col-md-6 col-md-offset-3';
                case 2:
                    return 'col-md-6';
                case 3:
                    return 'col-md-4';
                case 4:
                    return 'col-md-3';
                case 5:
                    return 'col-md-5th';
                case 7:
                    return 'col-md-4';
                case 8:
                    return 'col-md-3';
                default:
                    console.error("We only support up to 8 plans per interval.");
            }
        },

        process: function () {
            swal({
                title: "",
                text: '<i id="busy" class="fa fa-spinner fa-pulse" style="font-size: 8em; color: #ffffff;"></i>',
                html: true,
                allowEscapeKey: false,
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
                closeOnCancel: false,
                customClass: 'no-bg'
            });
        }
    }
});
