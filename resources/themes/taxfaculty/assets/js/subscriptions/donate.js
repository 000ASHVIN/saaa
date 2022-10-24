import moment from 'moment';
Vue.component('app-subscriptions-donation', {

    props: {
        user: null
    },

    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            
            forms: {
                busy:true,
                subscription: $.extend(true, new AppForm({
                    amount: 0,
                    first_name: '',
                    last_name:'',
                    email: '',
                    cell:[],
                    company_name: '',
                    address: '',
                    paymentOption: 'cc',
                    paymentStatus:'',
                    transactionId:null,
                    terms:false
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
        
    
    },

    watch: {
        'forms.subscription': function(val){
           console.log(val)
        }
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
         * Return the count of maximum features that can be selected
         */
        getMaxFeatures() {
            return this.selectedPlan.max_no_of_features;
        },

        /**
         * Check if we can select more features
         */
        canSelectMoreFeatures() {
            var maxFeatures = this.selectedPlan.max_no_of_features;
            if(this.countSelectedFeatures < maxFeatures || maxFeatures==0){
                return true;
            }else{
                swal({
                    type: 'success',
                    title: 'Good job!',
                    text: "You have selected "+maxFeatures+" Topics, Please proceed or clear your selection to reselect."
                })
                return false;
            }
        },

         /*
         * Determine if the plans have been loaded from the API.
         */
        selectedStaffLength: function () {
            return this.selectedStaff.length;
        },

        paymentOption: function () {
            return this.forms.subscription.paymentOption;
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
            return _.filter(this.filterPlan, function (plan) {
                return plan.interval == 'month' && plan.inactive != 1;
            });
        },


        /*
         * Get all of the plans that have a yearly interval.
         */
        yearlyPlans: function () {
            return _.filter(this.filterPlan, function (plan) {
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
                    if(this.getPlanPrice().length > 0){
                        return 'R ' + this.getPlanPrice()[0].price;
                    }else{
                        return 'R ' + this.selectedPlan.price;
                    }
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
       validateForm()
       {
        if(this.forms.subscription.first_name!="" && 
        this.forms.subscription.last_name!="" &&
        this.forms.subscription.email!="" &&
        this.forms.subscription.cell!="" &&
        this.forms.subscription.company_name!="" &&
        this.forms.subscription.address!="" &&
        this.forms.subscription.amount>0 
        ){
           this.forms.busy=false;
        }
       },
      
        popEft() {
            this.process();
            this.forms.subscription.busy = true;
            this.processEft(); 
            //this.processEft();
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
            //if(!this.is_practice_plan && plan.pricing_group>=0){
                setTimeout(() => {
                    this.getEftLink();
                    $('.app-first-field')
                        .filter(':visible:first')
                        .focus();
                }, 100);
            //}
        },

      
        selectEft: function()
        {
            setTimeout(() => {
                this.getEftLink();
                $('.app-first-field')
                    .filter(':visible:first')
                    .focus();
            }, 100);
        },

     

        getPlanPrice: function()
        {
            if(this.forms.subscription.pricing_group.length>0)
            {
                let staff = parseInt(this.selectedStaff.length)+parseInt(1);
                let plans =  _.max(this.forms.subscription.pricing_group.map(function(rec) {return rec.max_user}))
                let minuser = _.filter(this.forms.subscription.pricing_group, function (group) {
                    return parseInt(group.max_user) == 0;
                });
                if(minuser.length>0)
                {
                    let minuserCount = minuser[0].min_user;

                    
                    if(staff >= minuserCount) 
                    {
                        return minuser;
                    }
                }
                if(minuser.length == 0){
                    if(staff > plans) 
                    {
                        return _.filter(this.forms.subscription.pricing_group, function (group) {
                            return parseInt(group.max_user) == parseInt(plans);
                            
                        });    
                    }
                }
                return _.filter(this.forms.subscription.pricing_group, function (group) {
                    return parseInt(group.min_user) <= parseInt(staff) && parseInt(group.max_user) >= parseInt(staff);
                    
                });
            }
            return [];
        },
       

        /*
         * Initialize the registration process.
         */
        register: function () {
            this.forms.subscription.errors.forget();
            this.forms.subscription.busy = true;

            if(!this.forms.subscription.terms) {
                this.forms.subscription.errors.set({'terms': ['You must accept the Terms & Conditions to proceed.']});
                return true;
            }

            this.saveform();
        },
 
        getEftLink: function () {
            this.$http.post('/instant-eft', { donation:true, amount:this.forms.subscription.amount })
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
                    this.forms.subscription.transactionId = data.transaction_id;
                    this.forms.subscription.paymentStatus = 'success';
                    this.saveform();
                } else {
                    this.forms.subscription.paymentStatus = 'fail';
                    // this.saveform();
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
                    return 'col-md-4';
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
        },
        updateDebitDate: function()
        {
            $('#debit_options').modal('show');
        },
        saveform: function()
        {
            
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

            if(this.forms.subscription.paymentOption == 'cc') {

                if(this.user && !this.forms.subscription.card) {
                    swal({
                        type: 'warning',
                        title: 'Error',
                        text: "Unable to register without credit card, please select credit card"
                    })
                    this.forms.subscription.busy = false;
                    return;
                }
                else if(!this.user && (this.newcard.number=="" || this.newcard.holder=="" || this.newcard.exp_month=="" || this.newcard.exp_year=="" || this.newcard.cvv=="")) {
                    swal({
                        type: "warning",
                        title: "Please fill all details",
                        text: "Please fill all credit card details properly and try again"
                    })
                    this.forms.subscription.busy = false;
                    return false;
                }

                if(!this.user) {
                    this.newcard.return = `/`+ window.location.pathname + `?threeDs=yes&plan=${this.forms.subscription.plan}`;
                    this.forms.subscription.newcard = this.newcard;
                }
                
            }

            this.processPayment();
            this.$http.post('/donate/save', this.forms.subscription)
            .success((response) => {
                swal.close();
                this.forms.busy = false;
                console.log(response);
                if(response.status) {
                    setTimeout(function() {
                        swal({
                            type: 'success',
                            title: 'Success',
                            text: response.message
                        }, function() {
                            window.location.href="/donate";
                        });

                    }, 500);
                }
                else {

                    if(response.data) {
                        this.threeDs.url = response.data.redirect.url;
                        for (var i = response.data.redirect.parameters.length - 1; i >= 0; i--) {
                            this.threeDs[response.data.redirect.parameters[i].name] = response.data.redirect.parameters[i].value;
                        }
                        this.readyForThreeDs = true;
                        swal.close();
                    }
                    else {
                        setTimeout(function() {
                            swal({
                                type: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }, 500);
                    }
                }
                
                // var redirectUrl = '/dashboard/general';

                // if(this.forms.subscription.pi_cover) {
                //     redirectUrl = '/insurance'
                // }
                // window.location = redirectUrl;
            })
            .error((errors) => {
                swal.close();
            });
        },
        selectDebitOrder: function($event)
        {
            if(this.forms.subscription.debit_order_type == 'next day'){
                this.forms.subscription.debit.bill_at_next_available_date = 1;
            }else if(this.forms.subscription.debit_order_type == 'wait'){
                
            }else if(this.forms.subscription.debit_order_type == 'other'){
                
            }
            $('#debit_options').modal('hide');           
        }
        
    }
});
